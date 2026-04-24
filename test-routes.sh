#!/bin/bash

# Phase 1 Smoke Test - Check if all user routes return proper responses
# Usage: ./test-routes.sh

BASE_URL="http://localhost:8080"
ADMIN_URL="$BASE_URL/admin/users"

echo "🧪 Phase 1 Refactor - Route Smoke Tests"
echo "======================================="
echo ""

# Test index
echo "1️⃣ Testing GET /admin/users (index)"
curl -s -w "Status: %{http_code}\n" "$ADMIN_URL" | head -5
echo ""

# Test create form
echo "2️⃣ Testing GET /admin/users/create (show create form)"
curl -s -w "Status: %{http_code}\n" "$ADMIN_URL/create" | head -5
echo ""

# Note: Store, update, delete require POST/PUT/DELETE and valid data
# These require more complex setup with session/CSRF tokens
# Manual testing recommended via browser or API testing tool

echo "✅ Smoke test complete!"
echo ""
echo "Manual testing recommendations:"
echo "- Open http://localhost:8080/admin/users in browser"
echo "- Click 'Tambah User' to test create form (check master data loads)"
echo "- Click edit on existing user (check master data + cascade select)"
echo "- Verify no JavaScript errors in browser console"
