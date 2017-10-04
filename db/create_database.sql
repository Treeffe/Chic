create database if not exists chicncheap character set utf8 collate utf8_unicode_ci;
use chicncheap;

grant all privileges on chicncheap.* to 'chicncheap_user'@'localhost' identified by 'secret';