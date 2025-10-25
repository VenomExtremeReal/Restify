-- Script para limpar dados do banco SQLite
DELETE FROM messages;
DELETE FROM order_items;
DELETE FROM orders;
DELETE FROM restaurants WHERE email != 'admin@restify.com';
DELETE FROM sqlite_sequence WHERE name IN ('messages', 'order_items', 'orders', 'restaurants');
INSERT INTO sqlite_sequence (name, seq) VALUES ('restaurants', 1);