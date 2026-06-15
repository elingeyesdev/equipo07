#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${BASE_URL:-http://127.0.0.1:8081}"
TOTAL_REQUESTS="${TOTAL_REQUESTS:-200}"
CONCURRENCY="${CONCURRENCY:-20}"
ENDPOINT="${ENDPOINT:-/api/health}"

tmp_dir="$(mktemp -d)"
trap 'rm -rf "$tmp_dir"' EXIT

start_ns="$(date +%s%N)"

seq 1 "$TOTAL_REQUESTS" | xargs -P "$CONCURRENCY" -I {} sh -c '
  code="$(curl -s -o /dev/null -w "%{http_code} %{time_total}" "$0")" || code="000 0"
  printf "%s\n" "$code"
' "$BASE_URL$ENDPOINT" > "$tmp_dir/results.txt"

end_ns="$(date +%s%N)"
duration_ms=$(( (end_ns - start_ns) / 1000000 ))
successes="$(awk '$1 == 200 { count++ } END { print count + 0 }' "$tmp_dir/results.txt")"
failures=$(( TOTAL_REQUESTS - successes ))
avg_time="$(awk '{ sum += $2 } END { if (NR > 0) printf "%.4f", sum / NR; else print "0.0000" }' "$tmp_dir/results.txt")"
max_time="$(awk 'BEGIN { max = 0 } { if ($2 > max) max = $2 } END { printf "%.4f", max }' "$tmp_dir/results.txt")"
requests_per_second="$(awk -v total="$TOTAL_REQUESTS" -v ms="$duration_ms" 'BEGIN { if (ms > 0) printf "%.2f", total / (ms / 1000); else print "0.00" }')"

cat <<REPORT
Stress test: $BASE_URL$ENDPOINT
Total requests: $TOTAL_REQUESTS
Concurrency: $CONCURRENCY
Successful HTTP 200 responses: $successes
Failed responses: $failures
Total duration: ${duration_ms}ms
Requests per second: $requests_per_second
Average response time: ${avg_time}s
Max response time: ${max_time}s
REPORT

test "$failures" -eq 0
