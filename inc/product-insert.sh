i=0

while [ $i -le 50 ]
do
    i=$(($i+1))  # Increment the value of i by 1.

    # Print a message indicating the current product number.
    echo "Adding product no: $i ..."

    # Make an HTTP request using curl to the specified URL with a timestamp parameter.
    curl -X GET -H 'Cache-Control: no-store' "http://homeappliancepr.test/wp-json/homeappliancepr/v1/sync-products/?$(date +%s)" >> /dev/null

    # Print a message indicating that the ith product has been added.
    echo "$i th product Added"
done