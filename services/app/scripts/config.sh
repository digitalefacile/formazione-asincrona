#!/bin/bash

echo "------------    CHECK STATUS    ------------"
php admin/cli/upgrade.php --non-interactive --lang=en --is-pending || true
echo "------------  END CHECK STATUS  ------------"

echo
