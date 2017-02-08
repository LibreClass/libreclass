START=$(date +%s)

# variáveis do MySQL
HOST="HOST"
USER="libreclass-beta"
PASSWORD="SECRET"
DATABASE="libreclass-beta"

DATA=`/bin/date +%Y%m%d-%k%M%S`

# local onde será salvo o backup
LOCAL="/var/www/libreclass-backup/"

# nome do arquivo para backup
NOME="$DATABASE-$DATA"

mysqldump -u$USER -p$PASSWORD $DATABASE > $LOCAL$NOME.sql

7z a -t7z -m0=lzma -mx=9 -mfb=64 -md=32m -ms=on $LOCAL$NOME.7z $LOCAL$NOME.sql
rm $LOCAL$NOME.sql

END=$(date +%s)
RUNTIME=$((END-START))

SIZE=$(stat -c '%s' $LOCAL$NOME'.7z')

curl "http://HOST/libreclass-backup/index.php?name=$NOME.7z&size=$SIZE&runtime=$RUNTIME"

echo "$RUNTIME s runtime ($SIZE)"