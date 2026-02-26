#!/usr/bin/env bash
set -euo pipefail

BASE_URL=${BASE_URL:-http://127.0.0.1:8000}
MASTER_ID=${MASTER_ID:-2}
REQUEST_ID=${1:-}

if [[ -z "$REQUEST_ID" ]]; then
  echo "Usage: $0 <request_id>"
  echo "Optional env: BASE_URL, MASTER_ID"
  exit 1
fi

COOKIE_JAR=$(mktemp)
LOGIN_PAGE=$(mktemp)
MASTER_PAGE=$(mktemp)
RESP1=$(mktemp)
RESP2=$(mktemp)

cleanup() {
  rm -f "$COOKIE_JAR" "$LOGIN_PAGE" "$MASTER_PAGE" "$RESP1" "$RESP2"
}
trap cleanup EXIT

curl -s -c "$COOKIE_JAR" "$BASE_URL/login" > "$LOGIN_PAGE"
TOKEN=$(grep -o 'name="_token" value="[^"]*"' "$LOGIN_PAGE" | head -n1 | sed 's/.*value="\([^"]*\)".*/\1/')

if [[ -z "$TOKEN" ]]; then
  echo "Failed to extract CSRF token from /login"
  exit 1
fi

curl -s -b "$COOKIE_JAR" -c "$COOKIE_JAR" -X POST "$BASE_URL/login" \
  -d "_token=$TOKEN" \
  -d "user_id=$MASTER_ID" > /dev/null

curl -s -b "$COOKIE_JAR" "$BASE_URL/master/requests" > "$MASTER_PAGE"
TOKEN=$(grep -o 'name="_token" value="[^"]*"' "$MASTER_PAGE" | head -n1 | sed 's/.*value="\([^"]*\)".*/\1/')

if [[ -z "$TOKEN" ]]; then
  echo "Failed to extract CSRF token from /master/requests"
  exit 1
fi

curl -s -o /dev/null -w "%{http_code}" -b "$COOKIE_JAR" -X POST \
  "$BASE_URL/master/requests/$REQUEST_ID/take" \
  -d "_token=$TOKEN" -d "_method=PATCH" > "$RESP1" &
PID1=$!

curl -s -o /dev/null -w "%{http_code}" -b "$COOKIE_JAR" -X POST \
  "$BASE_URL/master/requests/$REQUEST_ID/take" \
  -d "_token=$TOKEN" -d "_method=PATCH" > "$RESP2" &
PID2=$!

wait "$PID1" "$PID2"

echo "Response 1: $(cat "$RESP1")"
echo "Response 2: $(cat "$RESP2")"
