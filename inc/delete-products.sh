i=0  # Initialize the variable i with the value 0.

while [ $i -le 500 ]  # Start a while loop that continues as long as i is less than or equal to 1000.
do
    i=$(($i+1))  # Increment the value of i by 1.

    # Print a message indicating the current product number.
    echo "Deleting product no: $i ..."

    # Make an HTTP request using curl to the specified URL with a timestamp parameter.
    curl -H 'Cache-Control: no-store' "http://homeappliancepr.test/wp-json/homeappliancepr/v1/delete-products/?$(date +%s)" >> /dev/null

    # Print a message indicating that the ith product has been added.
    echo "$i th product Deleted"
done