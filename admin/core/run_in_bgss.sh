#!/bin/bash

# Load environment variables
source ~/.bashrc

# Run indefinitely
while true
do
    busco -v
    # Navigate to the correct directory
    cd /disk3/default/V2/admin/core/

    # Run the PHP script
    php /disk3/default/V2/admin/core/busco.php

    # Wait for 1 minute before the next execution
    sleep 60
done

