/** POSTGRESQL **/
CREATE TABLE users(
    id serial primary key,
    name varchar(20) not null,
    surname varchar(30) not null,
    email varchar(100) unique CHECK (email LIKE '%@%'), 
    login varchar(50) unique not null,
    password varchar(150) not null,
    type varchar(10) not null
);

CREATE TABLE orders (
    id serial primary key,
    order_date date not null,
    user_id int not null,
    city varchar(60) not null,
    street varchar(60) not null,
    postal_code varchar(6) CHECK(postal_code LIKE'^[0-9]{2}-[0-9]{3}$') not null,
    amount double precision not null,
    foreign key (user_id) references users(id)
);

CREATE TABLE payments (
    id serial primary key,
    order_id int not null,
    payment_date date,
    amount double precision,
    status varchar(20),
    foreign key (order_id) references orders(id)
);

CREATE TABLE categories (
    id serial primary key,
    name varchar(50) not null unique
);

CREATE TABLE products (
    id serial primary key,
    manufacturer varchar(50) not null,
    model varchar(50) not null,
    price double precision not null,
    category_id int not null,
    photo varchar(50) not null,
    foreign key (category_id) references categories(id)
);

CREATE TABLE ram_details (
    id serial primary key,
    speed int not null,
    capacity varchar(10) not null,
    voltage double precision not null,
    module_count int not null,
    backlight boolean not null,
    cooling boolean not null,
    product_id int not null,
    foreign key (product_id) references products(id)
);

CREATE TABLE cpu_details (
    id serial primary key,
    speed double precision not null,
    architecture varchar(30) not null,
    supported_memory varchar(10) not null,
    cooling boolean not null,
    threads int not null,
    technological_process int not null,
    power_consumption int not null,
    product_id int not null,
    foreign key (product_id) references products(id)
);

CREATE TABLE cpu_cooling_details (
    id serial primary key,
    type varchar(20) not null,
    fan_count int not null,
    fan_size int not null,
    backlight boolean not null,
    material varchar(100) not null,
    radiator_size varchar(20) not null,
    compatibility text not null,
    product_id int not null,
    foreign key (product_id) references products(id)
);

CREATE TABLE motherboard_details (
    id serial primary key,
    chipset varchar(30) not null,
    form_factor varchar(10) not null,
    supported_memory varchar(100) not null,
    socket varchar(10) not null,
    cpu_architecture varchar(30) not null,
    internal_connectors text not null,
    external_connectors text not null,
    memory_slots int not null,
    audio_system varchar(40) not null,
    product_id int not null,
    foreign key (product_id) references products(id)
);

CREATE TABLE order_details (
    id serial primary key,
    order_id int not null,
    product_id int not null,
    quantity int not null,
    foreign key (order_id) references orders(id),
    foreign key (product_id) references products(id)
);

CREATE TABLE basket (
    id serial primary key,
    user_id int not null,
    product_id int not null,
    quantity int not null,
    foreign key (user_id) references users(id),
    foreign key (product_id) references products(id)
);

INSERT INTO users(name, surname, email, login, password) VALUES
('Piotr', 'Żywczak', 'piotrzywczak33@gmail.com', 'zywczak', '$2y$10$UrmxfLOwjd9vGfMnkE5xFOMnYKTwgeehHHIqcNJt5hkkl0bpZwFQC', 'admin'),
('Piotr', 'Żywczak', 'piotrzywczak@interia.pl', 'zywczok', '$2y$10$UrmxfLOwjd9vGfMnkE5xFOMnYKTwgeehHHIqcNJt5hkkl0bpZwFQC', 'user');

INSERT INTO categories(name) VALUES
('RAM'),
('CPU'),
('chłodzenie CPU'),
('płyta główna');

INSERT INTO products(manufacturer, model, price, category_id, photo) VALUES
('G.SKILL', 'Trident Z', 309, 1, 'G.SKILLTridentZ.png'),
('Corsair', 'Vengeance', 689, 1, 'CorsairVengeance.png'),
('GOODRAM', 'IRDM PRO', 375, 1, 'GOODRAMIRDMPRO.png'),
('Kingston FURY', 'Beast', 249, 1, 'KingstonFURYBeast.png'),
('AMD', 'Ryzen 5 5600X', 699, 2, 'Ryzen5600X.png'),
('AMD', 'Ryzen 5 7600X', 1149, 2, 'Ryzen7600X.png'),
('Intel', 'Core i5-14600KF', 1489, 2, 'i5-14600KF.png'),
('Intel', 'Core i5-13600K', 1549, 2, 'i5-13600K.png'),
('Fractal Design', 'Lumen S28', 659, 3, 'FractalDesignLumenS28.png'),
('NZXT', 'Kraken 280', 799, 3, 'NZXTKraken280.png'),
('be quiet!', 'Dark Rock 4', 329, 3, 'bequiet!DarkRock4.png'),
('Noctua', 'NH-D15', 619, 3, 'NoctuaNH-D15.png'),
('ASRock', 'B450 Steel Legend', 469, 4, 'B450SteelLegend.png'),
('ASRock', 'Z790 PG Riptide', 1079, 4, 'Z790PGRiptide.png'),
('ASUS', 'ROG STRIX Z790-F Gaming WIFI', 2249, 4, 'ROGSTRIXZ790-FGamingWIFI.png'),
('ASUS', 'ROG MAXIMUS Z790 DARK HERO', 3529, 4, 'ROGMAXIMUSZ790DARKHERO.png');

INSERT INTO ram_details(speed, capacity, voltage, module_count, backlight, cooling, product_id) VALUES
(3200, '16 GB', 1.35, 2, true, true, 1),
(6400, '32 GB', 1.4, 2, true, true, 2),
(3600, '32GB', 1.35, 2, false, true, 3),
(3600, '16 GB', 1.35, 2, true, true, 4);

INSERT INTO cpu_details(speed, architecture, supported_memory, cooling, threads, technological_process, power_consumption, product_id) VALUES
(3.7, 'Zen 3', 'DDR4-3200', true, 12, 7, 65, 5),
(4.7, 'Zen 4', 'DDR5-5200', false, 12, 5, 105, 6),
(3.5, 'Raptor Lake Refresh', 'DDR5-5600', false, 20, 10, 125, 7),
(3.5, 'Raptor Lake', 'DDR5-5600', false, 20, 10, 125, 8);

INSERT INTO cpu_cooling_details(type, fan_count, fan_size, backlight, material, radiator_size, compatibility, product_id) VALUES
('wodne', 2, 140, true, 'aluminium', '313x140x27', '2066, 2011-3, 2011, 1700, 1366, 1200, 1156, 1155, 1151, 1150, AM5, AM4, AM3+, AM3, AM2+, AM2, FM2+, FM2, FM1', 9),
('wodne', 2, 140, false, 'aluminium', '143x315x30', '1700, 1200, TR4, sTRX4, AM5, AM4', 10),
('powietrzne', 1, 135, false, 'aluminium + miedź', '159x136x96', '2066, 2011-3, 2011, 1700, 1366, 1200, 1156, 1155, 1151, 1150, AM5, AM4, AM2+, AM2, FM2+, FM2, FM1', 11),
('powietrzne', 2, 140, false, 'aluminium + miedź', '168x150x161', '2066, 2011, 1700, 1200, 1156, 1155, 1151, 1150, AM5, AM4, AM3+, AM3, AM2+, AM2, FM2+, FM2, FM1', 12);

INSERT INTO motherboard_details(chipset, form_factor, supported_memory, socket, cpu_architecture, internal_connectors, external_connectors, memory_slots, audio_system, product_id) VALUES
('AMD B450', 'ATX', 'DDR4-2666, DDR4-2400, DDR4-2133', 'AM4', 'Zen, Zen+, Zen2, Zen3', 'SATA III (6 Gb/s) - 6 szt., M.2 - 2 szt., PCIe 3.0 x16 - 2 szt., PCIe 2.0 x1 - 4 szt., USB 3.2 Gen. 1 - 1 szt., USB 2.0 - 2 szt., Złącze ARGB 3 pin - 1 szt., Złącze RGB 4 pin - 1 szt., Złącze COM - 1 szt., Front Panel Audio', 'HDMI - 1 szt., DisplayPort - 1 szt., RJ45 (LAN) - 1 szt., USB Type-C - 1 szt., USB 3.2 Gen. 1 - 4 szt., USB 3.2 Gen. 2 - 1 szt., USB 2.0 - 2 szt., PS/2 klawiatura/mysz - 1 szt., Audio jack - 5 szt., S/PDIF - 1 szt.', 4, 'Realtek ALC892', 13),
('Intel Z790', 'ATX', 'DDR5-4800, DDR5-4400', '1700', 'Alder Lake-S, Raptor Lake', 'SATA III (6 Gb/s) - 8 szt., M.2 PCIe NVMe 4.0 x4 - 4 szt., M.2 PCIe NVMe 5.0 x4 - 1 szt., PCIe 5.0 x16 - 1 szt., PCIe 4.0 x16 (tryb x4) - 1 szt., PCIe 3.0 x1 - 1 szt., USB 3.2 Gen. 2x2 Typu-C - 1 szt., USB 3.2 Gen. 1 - 1 szt., USB 2.0 - 2 szt., Złącze ARGB 3 pin - 3 szt., Złącze RGB 4 pin - 1 szt., Front Panel Audio, Złącze wentylatora CPU 4 pin - 1 szt., Złącze wentylatora SYS/CHA - 5 szt., Złącze pompy AIO - 1 szt., Złącze zasilania 8 pin - 2 szt., Złącze zasilania 24 pin - 1 szt., Złącze modułu TPM - 1 szt., Thunderbolt 4 - 1 szt.', 'HDMI - 1 szt., DisplayPort - 1 szt., RJ45 (LAN) 2.5 Gbps - 1 szt., USB Type-C - 1 szt., USB 3.2 Gen. 1 - 4 szt., USB 3.2 Gen. 2 - 2 szt., USB 2.0 - 2 szt., Audio jack - 3 szt.', 4, 'Realtek ALC897', 14),
('Intel Z790', 'ATX', 'DDR5-5600, DDR5-5400, DDR5-5200, DDR5-5000, DDR5-4800', '1700', 'Alder Lake-S, Raptor Lake', 'SATA III (6 Gb/s) - 4 szt., M.2 PCIe NVMe 4.0 x4 / SATA - 1 szt., M.2 PCIe NVMe 4.0 x4 - 3 szt., PCIe 5.0 x16 - 1 szt., PCIe 4.0 x16 (tryb x4) - 2 szt., PCIe 3.0 x1 - 1 szt., USB 3.2 Gen. 2x2 Typu-C - 1 szt., USB 3.2 Gen. 1 - 1 szt., USB 2.0 - 2 szt., Złącze ARGB 3 pin - 3 szt., Złącze RGB 4 pin - 1 szt., Front Panel Audio, Złącze wentylatora CPU 4 pin - 2 szt., Złącze wentylatora SYS/CHA - 5 szt., Złącze pompy AIO - 1 szt., Złącze zasilania 8 pin - 2 szt., Złącze zasilania 24 pin - 1 szt., Czujnik temperatury - 1 szt., Thunderbolt 4 - 1 szt.', 'HDMI - 1 szt., DisplayPort - 1 szt., RJ45 (LAN) 2.5 Gbps - 1 szt., USB Type-C - 2 szt., USB 3.2 Gen. 1 - 4 szt., USB 3.2 Gen. 2 - 2 szt., USB 2.0 - 4 szt., Audio jack - 5 szt., S/PDIF - 1 szt., Złącze anteny Wi-Fi - 2 szt., Przycisk Clear CMOS - 1 szt., Przycisk USB BIOS Flashback - 1 szt.', 4, 'Realtek ALC4080', 15),
('Intel Z790', 'ATX', 'DDR5-5600, DDR5-5400, DDR5-5200, DDR5-5000, DDR5-4800', '1700', 'Alder Lake-S, Raptor Lake, Raptor Lake Refresh', 'SATA III (6 Gb/s) - 4 szt., M.2 PCIe NVMe 4.0 x4 - 4 szt., M.2 PCIe NVMe 5.0 x4 - 1 szt., PCIe 5.0 x16 - 1 szt., PCIe 5.0 x16 (tryb x8) - 1 szt., PCIe 4.0 x4 - 1 szt., USB 3.2 Gen. 2x2 Typu-C - 1 szt., USB 3.2 Gen. 1 - 2 szt., USB 2.0 - 2 szt., Złącze ARGB 3 pin - 3 szt., Złącze RGB 4 pin - 1 szt., Front Panel Audio, Złącze wentylatora CPU 4 pin - 3 szt., Złącze wentylatora SYS/CHA - 4 szt., Złącze pompy AIO - 1 szt., Złącze zasilania 8 pin - 3 szt., Złącze zasilania 24 pin - 1 szt., Czujnik temperatury - 1 szt.', 'SATA III (6 Gb/s) - 4 szt., M.2 PCIe NVMe 4.0 x4 - 4 szt., M.2 PCIe NVMe 5.0 x4 - 1 szt., PCIe 5.0 x16 - 1 szt., PCIe 5.0 x16 (tryb x8) - 1 szt., PCIe 4.0 x4 - 1 szt., USB 3.2 Gen. 2x2 Typu-C - 1 szt., USB 3.2 Gen. 1 - 2 szt., USB 2.0 - 2 szt., Złącze ARGB 3 pin - 3 szt., Złącze RGB 4 pin - 1 szt., Front Panel Audio, Złącze wentylatora CPU 4 pin - 3 szt., Złącze wentylatora SYS/CHA - 4 szt., Złącze pompy AIO - 1 szt., Złącze zasilania 8 pin - 3 szt., Złącze zasilania 24 pin - 1 szt., Czujnik temperatury - 1 szt.', 4, 'Realtek ALC4082', 16);