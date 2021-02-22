# psycho

## how to use

`php index.php --target=$targetPath --code=$phpCode`

`$targetPath`: path to the php project

`$phpCode`: base64_encode value of code. Example `base64_encode('echo "hello"')`.

# projects driver supported

- Laravel
- Wordpress
- Plan project (which require only `vendor/autoload.php`)

# Build phar

`composer run build`
