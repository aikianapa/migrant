#!/bin/bash
find /var/www/migrant/uploads/tmp -type f -mtime 1 -exec rm -f {} \;