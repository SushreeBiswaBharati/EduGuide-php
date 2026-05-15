<?php
require_once 'helpers/sendmail.php';
if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    echo "Class exists\n";
} else {
    echo "Class does NOT exist\n";
    echo "Included files:\n";
    print_r(get_included_files());
}
