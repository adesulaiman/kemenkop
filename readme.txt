Berikut saya kirimkan aplikasi UMKM untuk dapat di deploy di server dev kemenkop,konfigurasi yang perlu di lakukan hanya di config.php saja
Untuk bagian ini hanya perlu sesuaikan connection postgres saja
$dbName = 'umkm';
$dbUser = 'postgres';
$dbPassword = 'postgres';
$dbHost = 'localhost';
$port = '5432';

untuk bagian ini sesuaikan link url yg di taro di dev env
$dir = 'http://localhost/application/umkm/';

untuk bagian ini sesuaikan config smtp email untuk auto mail
$user_mail = "rinadeshop88@gmail.com";
$pass_mail = "akbar153";
$name_mail = "UMKM";
$host_mail = "smtp.gmail.com";
$port_mail = 587;
$smtp_mail = "tls";