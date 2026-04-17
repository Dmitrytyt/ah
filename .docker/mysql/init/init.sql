-- init.sql
SELECT 'Creating database blog_test...';
CREATE DATABASE IF NOT EXISTS blog_test;
GRANT ALL PRIVILEGES ON blog_test.* TO 'blog'@'%';
FLUSH PRIVILEGES;
