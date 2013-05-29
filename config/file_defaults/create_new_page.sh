#!/bin/sh

# written in OS X bash


PATH_NAME="$1"
FILE_NAME="$2"

WWW_PATH="../../www"
TEMPLATE_PATH="../../templates/www"
JS_PATH="../../www/js"
CSS_PATH="../../www/css"

echo ""

# CREATE FOLDERS 
echo "creating folder ../../www/$PATH_NAME"
mkdir ../../www/$PATH_NAME

echo "creating folder ../../templates/www/$PATH_NAME"
mkdir ../../templates/www/$PATH_NAME

echo "creating folder ../../www/js/$PATH_NAME"
mkdir ../../www/js/$PATH_NAME

echo "creating folder ../../www/css/$PATH_NAME"
mkdir ../../www/css/$PATH_NAME

echo ""
# STAGING FILE
echo "copying and updating file names in $WWW_PATH/$PATH_NAME/$FILE_NAME.php"
cp php.txt $WWW_PATH/$PATH_NAME/$FILE_NAME.php
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $WWW_PATH/$PATH_NAME/$FILE_NAME.php
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $WWW_PATH/$PATH_NAME/$FILE_NAME.php
rm $WWW_PATH/$PATH_NAME/$FILE_NAME.php-e


# TEMPLATE BASE FILE
echo "copying and updating file names in $TEMPLATE_PATH/$PATH_NAME/$FILE_NAME.tpl.php"
cp tpl.php.txt  $TEMPLATE_PATH/$PATH_NAME/$FILE_NAME.tpl.php
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $TEMPLATE_PATH/$PATH_NAME/$FILE_NAME.tpl.php
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $TEMPLATE_PATH/$PATH_NAME/$FILE_NAME.tpl.php
rm $TEMPLATE_PATH/$PATH_NAME/$FILE_NAME.tpl.php-e

# JS FILE
echo "copying and updating file names in $JS_PATH/$PATH_NAME/$FILE_NAME.js"
cp js.txt  $JS_PATH/$PATH_NAME/$FILE_NAME.js
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $JS_PATH/$PATH_NAME/$FILE_NAME.js
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $JS_PATH/$PATH_NAME/$FILE_NAME.js
rm $JS_PATH/$PATH_NAME/$FILE_NAME.js-e


# JS INLINE FILE
echo "copying and updating file names in $JS_PATH/$PATH_NAME/$FILE_NAME.js.php"
cp js.php.txt  $JS_PATH/$PATH_NAME/$FILE_NAME.js.php
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $JS_PATH/$PATH_NAME/$FILE_NAME.js.php
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $JS_PATH/$PATH_NAME/$FILE_NAME.js.php
rm $JS_PATH/$PATH_NAME/$FILE_NAME.js.php-e


# CSS FILE
echo "copying and updating file names in $CSS_PATH/$PATH_NAME/$FILE_NAME.css"
cp css.txt  $CSS_PATH/$PATH_NAME/$FILE_NAME.css
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $CSS_PATH/$PATH_NAME/$FILE_NAME.css
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $CSS_PATH/$PATH_NAME/$FILE_NAME.css
rm $CSS_PATH/$PATH_NAME/$FILE_NAME.css-e


# CSS INLINE FILE
echo "copying and updating file names in $CSS_PATH/$PATH_NAME/$FILE_NAME.css.php"
cp css.php.txt  $CSS_PATH/$PATH_NAME/$FILE_NAME.css.php
sed -i -e "s/{{file_name}}/$FILE_NAME/g" $CSS_PATH/$PATH_NAME/$FILE_NAME.css.php
sed -i -e "s/{{file_path}}/$PATH_NAME/g" $CSS_PATH/$PATH_NAME/$FILE_NAME.css.php
rm $CSS_PATH/$PATH_NAME/$FILE_NAME.css.php-e


