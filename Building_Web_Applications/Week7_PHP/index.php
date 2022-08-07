<!DOCTYPE html>
<html>
    <head>
         <title> Gabriel de Almeida Miki MD5 Cracker </title>
    </head>

    <body>
        <h1> Gabriel de Almeida Miki MD5 cracker </h1>

        <p>
            This application takes an MD5 hash of a two-character lower case string and 
            attempts to hash all two-character combinations to determine the original two characters.
        </p>

        <pre>
Debug Output:

<?php
$goodtext = "Not found";

// If there is no parameter, this code is all skipped
if (isset($_GET['md5']) ) {
    $time_pre = microtime(true);
    $md5 = $_GET['md5'];
    $count = 0;

    // This is our alphabet
    $txt = "0123456789";
    $show = 15;

    // Outer loop to go through the alphabet for the
    // first position in our "possible" pre-hash
    // text
    for($i = 0; $i < strlen($txt); $i++) {
        $ch1 = $txt[$i];   // The first of two characters

        for($j = 0; $j < strlen($txt); $j++) {
            $ch2 = $txt[$j];  // Our second character

            for($k = 0; $k < strlen($txt); $k++) {
                $ch3 = $txt[$k];  // Our third character

                for($m = 0; $m < strlen($txt); $m++) {
                    $ch4 = $txt[$m];  // Our fourth character
        
                    // Concatenate the two characters together to 
                    // form the "possible" pre-hash text
                    $try = $ch1 . $ch2 . $ch3 . $ch4;

                    // Run the hash and then check to see if we match
                    $check = hash('md5', $try);
                    if ($check == $md5) {
                        $goodtext = $try;
                        break;   // Exit the inner loop
                    }

                    // Debug output until $show hits 0
                    if ($show > 0) {
                        print "$check $try\n";
                        $show = $show - 1;
                    }

                    $count++;
                }
            }
        }
    }
    // Compute elapsed time
    $time_post = microtime(true);
    print "Elapsed time: ";
    print $time_post-$time_pre;
    print "\n";
    print "Number of tryals: ";
    print $count;
    print "\n";
}
?>
</pre>

<!-- Use the very short syntax and call htmlentities() -->
        <p> Original Text: <?= htmlentities($goodtext); ?> </p>

        <form style = "margin-bottom: 1em;">
            <input type="text" name="md5" size="60">
            <input type="submit" value="Crack MD5">
        </form>

        <a href = "index.php"> Reset Page </a>
    </body>
</html>