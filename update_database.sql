-- Drop existing database if exists and create new one
DROP DATABASE IF EXISTS heritage_culture;
CREATE DATABASE heritage_culture;
USE heritage_culture;

-- Create states table
CREATE TABLE IF NOT EXISTS states (
    state_id INT PRIMARY KEY AUTO_INCREMENT,
    state_name VARCHAR(100) NOT NULL,
    description TEXT,
    image_url VARCHAR(255)
);

-- Create heritage_places table
CREATE TABLE IF NOT EXISTS heritage_places (
    place_id INT PRIMARY KEY AUTO_INCREMENT,
    state_id INT,
    place_name VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    location VARCHAR(255),
    FOREIGN KEY (state_id) REFERENCES states(state_id)
);

-- Create culture table
CREATE TABLE IF NOT EXISTS culture (
    culture_id INT PRIMARY KEY AUTO_INCREMENT,
    state_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    type VARCHAR(50),
    FOREIGN KEY (state_id) REFERENCES states(state_id)
);

-- Create traditions table
CREATE TABLE IF NOT EXISTS traditions (
    tradition_id INT PRIMARY KEY AUTO_INCREMENT,
    state_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    image_url VARCHAR(255),
    category VARCHAR(100),
    FOREIGN KEY (state_id) REFERENCES states(state_id)
);

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE,
    role ENUM('admin', 'user') DEFAULT 'user'
);

-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create user_sessions table
CREATE TABLE IF NOT EXISTS user_sessions (
    session_id VARCHAR(255) PRIMARY KEY,
    user_id INT NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert states data
INSERT INTO states (state_name, description, image_url) VALUES
('Rajasthan', 'Land of Kings, known for its rich cultural heritage and magnificent forts', 'images/states/rajasthan.jpg'),
('Kerala', 'Gods own country, famous for its backwaters and traditional art forms', 'images/states/kerala.jpg'),
('Tamil Nadu', 'Land of temples and classical dance forms', 'images/states/tamil-nadu.jpg'),
('Gujarat', 'Land of festivals and vibrant culture', 'images/states/gujarat.jpg'),
('Uttar Pradesh', 'Land of ancient cities and cultural heritage', 'images/states/uttar-pradesh.jpg'),
('Maharashtra', 'Land of forts and Maratha culture', 'images/states/maharashtra.jpg'),
('Karnataka', 'Land of ancient temples and classical music', 'images/states/karnataka.jpg'),
('West Bengal', 'Land of literature and cultural renaissance', 'images/states/west-bengal.jpg'),
('Punjab', 'Land of vibrant culture and Sikh heritage', 'images/states/punjab.jpg'),
('Odisha', 'Land of temples and classical dance', 'images/states/odisha.jpg'),
('Assam', 'Land of tea gardens and Bihu dance', 'images/states/Assam.jpg'),
('Goa', 'Land of beaches and Portuguese heritage', 'images/states/Goa.jpg'),
('Bihar', 'Land of ancient learning and Buddhist heritage', 'images/states/bihar.jpg');

-- Insert heritage places data
INSERT INTO heritage_places (state_id, place_name, description, image_url, location) VALUES
(1, 'Amber Fort', 'Historic fort with beautiful architecture', 'images/heritage/amber-fort.jpg', 'Jaipur'),
(1, 'Hawa Mahal', 'Palace of winds with intricate lattice work', 'images/heritage/hawa-mahal.jpg', 'Jaipur'),
(2, 'Padmanabhaswamy Temple', 'Ancient temple with rich history', 'images/heritage/padmanabhaswamy.jpg', 'Thiruvananthapuram'),
(5, 'Taj Mahal', 'Iconic white marble mausoleum built by Shah Jahan', 'images/heritage/taj-mahal.jpg', 'Agra'),
(5, 'Fatehpur Sikri', 'Mughal capital city with stunning architecture', 'images/heritage/fatehpur-sikri.jpg', 'Agra'),
(6, 'Ajanta Caves', 'Ancient Buddhist cave paintings', 'images/heritage/ajanta-caves.jpg', 'Aurangabad'),
(6, 'Ellora Caves', 'Rock-cut temples and monasteries', 'images/heritage/ellora-caves.jpg', 'Aurangabad'),
(7, 'Hampi', 'Ruins of the Vijayanagara Empire', 'images/heritage/hampi.jpg', 'Hampi'),
(7, 'Mysore Palace', 'Royal palace of the Wodeyar dynasty', 'images/heritage/mysore-palace.jpg', 'Mysore'),
(8, 'Victoria Memorial', 'Marble monument dedicated to Queen Victoria', 'images/heritage/victoria-memorial.jpg', 'Kolkata'),
(8, 'Howrah Bridge', 'Iconic cantilever bridge over Hooghly River', 'images/heritage/howrah-bridge.jpg', 'Kolkata'),
(9, 'Golden Temple', 'Sacred Sikh gurdwara with golden dome', 'images/heritage/golden-temple.jpg', 'Amritsar'),
(10, 'Konark Sun Temple', '13th-century temple dedicated to the Sun God', 'images/heritage/konark-temple.jpg', 'Konark'),
(11, 'Kaziranga National Park', 'UNESCO World Heritage Site for one-horned rhinos', 'images/heritage/kaziranga.jpg', 'Kaziranga'),
(12, 'Basilica of Bom Jesus', 'UNESCO World Heritage Site with St. Francis Xavier relics', 'images/heritage/basilica.jpg', 'Old Goa'),
(1, 'Jaisalmer Fort', 'Golden fort in the Thar Desert', 'images/heritage/jaisalmer-fort.jpg', 'Jaisalmer'),
(1, 'City Palace', 'Royal palace complex in Jaipur', 'images/heritage/city-palace.jpg', 'Jaipur'),
(2, 'Mattancherry Palace', 'Portuguese palace with Kerala murals', 'images/heritage/mattancherry.jpg', 'Kochi'),
(2, 'Bekal Fort', 'Largest fort in Kerala', 'images/heritage/bekal-fort.jpg', 'Kasaragod'),
(3, 'Meenakshi Temple', 'Ancient temple with thousand pillars', 'images/heritage/meenakshi.jpg', 'Madurai'),
(3, 'Brihadeeswarar Temple', 'UNESCO World Heritage Site', 'images/heritage/brihadeeswarar.jpg', 'Thanjavur'),
(4, 'Rani ki Vav', 'Stepwell with intricate carvings', 'images/heritage/rani-ki-vav.jpg', 'Patan'),
(4, 'Somnath Temple', 'One of the twelve Jyotirlingas', 'images/heritage/somnath.jpg', 'Somnath'),
(5, 'Varanasi Ghats', 'Sacred riverfront steps', 'images/heritage/varanasi-ghats.jpg', 'Varanasi'),
(5, 'Sarnath', 'Buddhist pilgrimage site', 'images/heritage/sarnath.jpg', 'Varanasi'),
(6, 'Gateway of India', 'Historic arch monument', 'images/heritage/gateway.jpg', 'Mumbai'),
(6, 'Chhatrapati Shivaji Terminus', 'UNESCO World Heritage railway station', 'images/heritage/cst.jpg', 'Mumbai'),
(7, 'Gol Gumbaz', 'Mausoleum with whispering gallery', 'images/heritage/gol-gumbaz.jpg', 'Vijayapura'),
(7, 'Belur Temple', 'Hoysala architecture masterpiece', 'images/heritage/belur.jpg', 'Hassan'),
(8, 'Dakshineswar Temple', 'Temple on the banks of Hooghly', 'images/heritage/dakshineswar.jpg', 'Kolkata'),
(8, 'Sundarbans', 'Largest mangrove forest', 'images/heritage/sundarbans.jpg', 'South 24 Parganas'),
(9, 'Jallianwala Bagh', 'Historic garden and memorial', 'images/heritage/jallianwala.jpg', 'Amritsar'),
(9, 'Qila Mubarak', 'Ancient fort in Bathinda', 'images/heritage/qila-mubarak.jpg', 'Bathinda'),
(10, 'Jagannath Temple', 'Famous temple in Puri', 'images/heritage/jagannath.jpg', 'Puri'),
(10, 'Lingaraj Temple', 'Ancient temple in Bhubaneswar', 'images/heritage/lingaraj.jpg', 'Bhubaneswar'),
(11, 'Kamakhya Temple', 'Ancient temple of Goddess Kamakhya', 'images/heritage/kamakhya.jpg', 'Guwahati'),
(11, 'Majuli Island', 'Worlds largest river island', 'images/heritage/majuli.jpg', 'Majuli'),
(12, 'Chapora Fort', 'Historic fort with scenic views', 'images/heritage/chapora.jpg', 'Chapora'),
(12, 'Se Cathedral', 'Largest church in Asia', 'images/heritage/se-cathedral.jpg', 'Old Goa'),
(5, 'Bodh Gaya', 'Sacred Buddhist pilgrimage site where Buddha attained enlightenment', 'images/heritage/bodh-gaya.jpg', 'Bodh Gaya'),
(5, 'Nalanda University', 'Ancient center of learning and Buddhist monastery', 'images/heritage/nalanda.jpg', 'Nalanda'),
(5, 'Vikramshila University', 'Ancient Buddhist learning center', 'images/heritage/vikramshila.jpg', 'Bhagalpur'),
(5, 'Mahabodhi Temple', 'UNESCO World Heritage Site, sacred Buddhist temple', 'images/heritage/mahabodhi.jpg', 'Bodh Gaya'),
(9, 'Wagah Border', 'Famous border ceremony between India and Pakistan', 'images/heritage/wagah.jpg', 'Amritsar'),
(9, 'Anandpur Sahib', 'Historic Sikh pilgrimage site', 'images/heritage/anandpur.jpg', 'Anandpur Sahib'),
(9, 'Sheesh Mahal', 'Palace of Mirrors in Patiala', 'images/heritage/sheesh-mahal.jpg', 'Patiala'),
(9, 'Qila Mubarak', 'Historic fort in Bathinda', 'images/heritage/qila-mubarak.jpg', 'Bathinda'),
(13, 'Bodh Gaya', 'Sacred Buddhist pilgrimage site where Buddha attained enlightenment', 'images/heritage/bodh-gaya.jpg', 'Bodh Gaya'),
(13, 'Nalanda University', 'Ancient center of learning and Buddhist monastery', 'images/heritage/nalanda.jpg', 'Nalanda'),
(13, 'Vikramshila University', 'Ancient Buddhist learning center', 'images/heritage/vikramshila.jpg', 'Bhagalpur'),
(13, 'Mahabodhi Temple', 'UNESCO World Heritage Site, sacred Buddhist temple', 'images/heritage/mahabodhi.jpg', 'Bodh Gaya'),
(13, 'Pawapuri', 'Sacred Jain pilgrimage site', 'images/heritage/pawapuri.jpg', 'Nalanda'),
(13, 'Barabar Caves', 'Ancient rock-cut caves', 'images/heritage/barabar.jpg', 'Jehanabad'),
(13, 'Patna Museum', 'Historical museum with rich artifacts', 'images/heritage/patna-museum.jpg', 'Patna'),
(13, 'Golghar', 'Historic granary with panoramic views', 'images/heritage/golghar.jpg', 'Patna');

-- Insert culture data
INSERT INTO culture (state_id, title, description, image_url, type) VALUES
(1, 'Ghoomar Dance', 'Traditional folk dance of Rajasthan', 'images/culture/ghoomar.jpg', 'dance'),
(2, 'Kathakali', 'Classical dance-drama of Kerala', 'images/culture/kathakali.jpg', 'dance'),
(3, 'Bharatanatyam', 'Classical dance form of Tamil Nadu', 'images/culture/bharatanatyam.jpg', 'dance'),
(5, 'Kathak', 'Classical dance form with intricate footwork', 'images/culture/kathak.jpg', 'dance'),
(5, 'Awadhi Cuisine', 'Royal cuisine of Lucknow', 'images/culture/awadhi-cuisine.jpg', 'cuisine'),
(6, 'Lavani', 'Traditional dance form of Maharashtra', 'images/culture/lavani.jpg', 'dance'),
(6, 'Warli Art', 'Tribal art form with geometric patterns', 'images/culture/warli-art.jpg', 'art'),
(7, 'Yakshagana', 'Traditional theatre form of Karnataka', 'images/culture/yakshagana.jpg', 'theatre'),
(7, 'Mysore Painting', 'Classical South Indian painting style', 'images/culture/mysore-painting.jpg', 'art'),
(8, 'Rabindra Sangeet', 'Songs composed by Rabindranath Tagore', 'images/culture/rabindra-sangeet.jpg', 'music'),
(8, 'Durga Puja', 'Major festival celebrating Goddess Durga', 'images/culture/durga-puja.jpg', 'festival'),
(9, 'Bhangra', 'Energetic folk dance of Punjab', 'images/culture/bhangra.jpg', 'dance'),
(9, 'Punjabi Cuisine', 'Rich and flavorful cuisine of Punjab', 'images/culture/punjabi-cuisine.jpg', 'cuisine'),
(10, 'Odissi', 'Classical dance form of Odisha', 'images/culture/odissi.jpg', 'dance'),
(10, 'Pattachitra', 'Traditional cloth-based scroll painting', 'images/culture/pattachitra.jpg', 'art'),
(11, 'Bihu Dance', 'Folk dance of Assam', 'images/culture/bihu.jpg', 'dance'),
(11, 'Assamese Silk', 'Traditional silk weaving', 'images/culture/assamese-silk.jpg', 'craft'),
(12, 'Fado Music', 'Portuguese-influenced music form', 'images/culture/fado.jpg', 'music'),
(12, 'Goan Carnival', 'Colorful pre-Lenten festival', 'images/culture/goan-carnival.jpg', 'festival'),
(1, 'Kalbelia Dance', 'Snake charmers dance form', 'images/culture/kalbelia.jpg', 'dance'),
(1, 'Rajasthani Cuisine', 'Rich and spicy cuisine', 'images/culture/rajasthani-cuisine.jpg', 'cuisine'),
(2, 'Mohiniyattam', 'Classical dance form', 'images/culture/mohiniyattam.jpg', 'dance'),
(2, 'Kerala Cuisine', 'Traditional cuisine with coconut', 'images/culture/kerala-cuisine.jpg', 'cuisine'),
(3, 'Carnatic Music', 'Classical music tradition', 'images/culture/carnatic.jpg', 'music'),
(3, 'Tanjore Painting', 'Classical painting style', 'images/culture/tanjore.jpg', 'art'),
(4, 'Garba', 'Traditional dance form', 'images/culture/garba.jpg', 'dance'),
(4, 'Gujarati Cuisine', 'Vegetarian cuisine', 'images/culture/gujarati-cuisine.jpg', 'cuisine'),
(5, 'Ram Lila', 'Traditional theatre form', 'images/culture/ram-lila.jpg', 'theatre'),
(5, 'Banarasi Sarees', 'Traditional silk weaving', 'images/culture/banarasi.jpg', 'craft'),
(6, 'Powada', 'Traditional ballad singing', 'images/culture/powada.jpg', 'music'),
(6, 'Kolhapuri Chappals', 'Traditional footwear', 'images/culture/kolhapuri.jpg', 'craft'),
(7, 'Dollu Kunitha', 'Traditional drum dance', 'images/culture/dollu.jpg', 'dance'),
(7, 'Mysore Pak', 'Traditional sweet', 'images/culture/mysore-pak.jpg', 'cuisine'),
(8, 'Baul Music', 'Traditional folk music', 'images/culture/baul.jpg', 'music'),
(8, 'Kantha Embroidery', 'Traditional embroidery', 'images/culture/kantha.jpg', 'craft'),
(9, 'Giddha', 'Traditional folk dance', 'images/culture/giddha.jpg', 'dance'),
(9, 'Punjabi Folk Music', 'Traditional music', 'images/culture/punjabi-folk.jpg', 'music'),
(10, 'Gotipua', 'Traditional dance form', 'images/culture/gotipua.jpg', 'dance'),
(10, 'Odisha Cuisine', 'Traditional cuisine', 'images/culture/odisha-cuisine.jpg', 'cuisine'),
(11, 'Sattriya', 'Classical dance form', 'images/culture/sattriya.jpg', 'dance'),
(11, 'Assamese Cuisine', 'Traditional cuisine', 'images/culture/assamese-cuisine.jpg', 'cuisine'),
(12, 'Mando', 'Traditional dance form', 'images/culture/mando.jpg', 'dance'),
(12, 'Goan Cuisine', 'Portuguese influenced cuisine', 'images/culture/goan-cuisine.jpg', 'cuisine'),
(5, 'Madhubani Painting', 'Traditional folk painting style', 'images/culture/madhubani.jpg', 'art'),
(5, 'Bhojpuri Music', 'Traditional folk music of Bihar', 'images/culture/bhojpuri.jpg', 'music'),
(5, 'Bihari Cuisine', 'Traditional cuisine with litti-chokha', 'images/culture/bihari-cuisine.jpg', 'cuisine'),
(5, 'Chhath Puja', 'Ancient festival dedicated to Sun God', 'images/culture/chhath.jpg', 'festival'),
(9, 'Gatka', 'Traditional Sikh martial art', 'images/culture/gatka.jpg', 'martial_art'),
(9, 'Punjabi Sufi Music', 'Traditional Sufi music tradition', 'images/culture/sufi.jpg', 'music'),
(9, 'Punjabi Folk Dance', 'Traditional dance forms', 'images/culture/punjabi-folk-dance.jpg', 'dance'),
(9, 'Punjabi Literature', 'Rich literary tradition', 'images/culture/punjabi-literature.jpg', 'literature'),
(13, 'Madhubani Painting', 'Traditional folk painting style', 'images/culture/madhubani.jpg', 'art'),
(13, 'Bhojpuri Music', 'Traditional folk music of Bihar', 'images/culture/bhojpuri.jpg', 'music'),
(13, 'Bihari Cuisine', 'Traditional cuisine with litti-chokha', 'images/culture/bihari-cuisine.jpg', 'cuisine'),
(13, 'Chhath Puja', 'Ancient festival dedicated to Sun God', 'images/culture/chhath.jpg', 'festival'),
(13, 'Jhijhiya Dance', 'Traditional folk dance', 'images/culture/jhijhiya.jpg', 'dance'),
(13, 'Bidesia', 'Traditional folk theatre', 'images/culture/bidesia.jpg', 'theatre'),
(13, 'Manjusha Art', 'Traditional folk art', 'images/culture/manjusha.jpg', 'art'),
(13, 'Bihar Saree', 'Traditional handloom weaving', 'images/culture/bihar-saree.jpg', 'craft');

-- Insert traditions data
INSERT INTO traditions (state_id, title, description, image_url, category) VALUES
(1, 'Turban Tying', 'Traditional headgear of Rajasthan', 'images/traditions/turban.jpg', 'clothing'),
(2, 'Onam Festival', 'Harvest festival of Kerala', 'images/traditions/onam.jpg', 'festival'),
(3, 'Pongal', 'Harvest festival of Tamil Nadu', 'images/traditions/pongal.jpg', 'festival'),
(5, 'Kumbh Mela', 'Largest religious gathering in the world', 'images/traditions/kumbh-mela.jpg', 'festival'),
(5, 'Chikankari', 'Traditional embroidery of Lucknow', 'images/traditions/chikankari.jpg', 'craft'),
(6, 'Ganesh Chaturthi', 'Festival celebrating Lord Ganesha', 'images/traditions/ganesh-chaturthi.jpg', 'festival'),
(6, 'Paithani Sarees', 'Traditional silk sarees of Maharashtra', 'images/traditions/paithani.jpg', 'clothing'),
(7, 'Dasara Festival', '10-day festival in Mysore', 'images/traditions/dasara.jpg', 'festival'),
(7, 'Mysore Silk', 'Traditional silk weaving', 'images/traditions/mysore-silk.jpg', 'craft'),
(8, 'Durga Puja', 'Major festival celebrating Goddess Durga', 'images/traditions/durga-puja.jpg', 'festival'),
(8, 'Terracotta Craft', 'Traditional clay craft', 'images/traditions/terracotta.jpg', 'craft'),
(9, 'Lohri', 'Harvest festival of Punjab', 'images/traditions/lohri.jpg', 'festival'),
(9, 'Phulkari', 'Traditional embroidery of Punjab', 'images/traditions/phulkari.jpg', 'craft'),
(10, 'Rath Yatra', 'Chariot festival of Lord Jagannath', 'images/traditions/rath-yatra.jpg', 'festival'),
(10, 'Silver Filigree', 'Traditional silver craft', 'images/traditions/silver-filigree.jpg', 'craft'),
(11, 'Bihu Festival', 'Harvest festival of Assam', 'images/traditions/bihu-festival.jpg', 'festival'),
(11, 'Bamboo Craft', 'Traditional bamboo weaving', 'images/traditions/bamboo-craft.jpg', 'craft'),
(12, 'Christmas', 'Major festival with Portuguese influence', 'images/traditions/goa-christmas.jpg', 'festival'),
(12, 'Feni Making', 'Traditional liquor making', 'images/traditions/feni.jpg', 'craft'),
(1, 'Gangaur Festival', 'Celebration of marital harmony', 'images/traditions/gangaur.jpg', 'festival'),
(1, 'Blue Pottery', 'Traditional ceramic craft', 'images/traditions/blue-pottery.jpg', 'craft'),
(2, 'Theyyam', 'Ritual dance form', 'images/traditions/theyyam.jpg', 'festival'),
(2, 'Kerala Mural', 'Traditional painting style', 'images/traditions/kerala-mural.jpg', 'art'),
(3, 'Jallikattu', 'Traditional bull-taming sport', 'images/traditions/jallikattu.jpg', 'sport'),
(3, 'Tanjore Dolls', 'Traditional handicraft', 'images/traditions/tanjore-dolls.jpg', 'craft'),
(4, 'Navratri', 'Nine-night festival', 'images/traditions/navratri.jpg', 'festival'),
(4, 'Kutch Embroidery', 'Traditional embroidery', 'images/traditions/kutch.jpg', 'craft'),
(5, 'Kashi Vishwanath Temple', 'Traditional temple rituals', 'images/traditions/kashi.jpg', 'ritual'),
(5, 'Banarasi Silk', 'Traditional silk weaving', 'images/traditions/banarasi-silk.jpg', 'craft'),
(6, 'Gudi Padwa', 'New Year celebration', 'images/traditions/gudi-padwa.jpg', 'festival'),
(6, 'Warli Painting', 'Traditional tribal art', 'images/traditions/warli.jpg', 'art'),
(7, 'Ugadi', 'New Year celebration', 'images/traditions/ugadi.jpg', 'festival'),
(7, 'Bidriware', 'Traditional metal craft', 'images/traditions/bidriware.jpg', 'craft'),
(8, 'Poila Boishakh', 'Bengali New Year', 'images/traditions/poila.jpg', 'festival'),
(8, 'Dhokra Craft', 'Traditional metal casting', 'images/traditions/dhokra.jpg', 'craft'),
(9, 'Baisakhi', 'Harvest festival', 'images/traditions/baisakhi.jpg', 'festival'),
(9, 'Punjabi Jutti', 'Traditional footwear', 'images/traditions/jutti.jpg', 'craft'),
(10, 'Raja Parba', 'Celebration of womanhood', 'images/traditions/raja.jpg', 'festival'),
(10, 'Pattachitra', 'Traditional scroll painting', 'images/traditions/pattachitra.jpg', 'art'),
(11, 'Bihu', 'Harvest festival', 'images/traditions/bihu.jpg', 'festival'),
(11, 'Assam Tea', 'Traditional tea culture', 'images/traditions/assam-tea.jpg', 'craft'),
(12, 'Shigmo', 'Spring festival', 'images/traditions/shigmo.jpg', 'festival'),
(12, 'Goan Pottery', 'Traditional pottery', 'images/traditions/goan-pottery.jpg', 'craft'),
(5, 'Sonepur Mela', 'Largest cattle fair in Asia', 'images/traditions/sonepur.jpg', 'festival'),
(5, 'Sujani Embroidery', 'Traditional embroidery style', 'images/traditions/sujani.jpg', 'craft'),
(5, 'Manjusha Art', 'Traditional folk art', 'images/traditions/manjusha.jpg', 'art'),
(5, 'Bihar Saree', 'Traditional handloom weaving', 'images/traditions/bihar-saree.jpg', 'clothing'),
(9, 'Gurpurab', 'Celebration of Sikh Gurus birthdays', 'images/traditions/gurpurab.jpg', 'festival'),
(9, 'Punjabi Phulkari', 'Traditional embroidery', 'images/traditions/phulkari.jpg', 'craft'),
(9, 'Punjabi Jutti', 'Traditional footwear', 'images/traditions/punjabi-jutti.jpg', 'clothing'),
(9, 'Punjabi Turban', 'Traditional headgear', 'images/traditions/punjabi-turban.jpg', 'clothing'),
(13, 'Sonepur Mela', 'Largest cattle fair in Asia', 'images/traditions/sonepur.jpg', 'festival'),
(13, 'Sujani Embroidery', 'Traditional embroidery style', 'images/traditions/sujani.jpg', 'craft'),
(13, 'Manjusha Art', 'Traditional folk art', 'images/traditions/manjusha.jpg', 'art'),
(13, 'Bihar Saree', 'Traditional handloom weaving', 'images/traditions/bihar-saree.jpg', 'clothing'),
(13, 'Buddha Purnima', 'Celebration of Buddha birth anniversary', 'images/traditions/buddha-purnima.jpg', 'festival'),
(13, 'Makar Sankranti', 'Harvest festival', 'images/traditions/makar-sankranti.jpg', 'festival'),
(13, 'Tikuli Art', 'Traditional glass painting', 'images/traditions/tikuli.jpg', 'art'),
(13, 'Bihar Pottery', 'Traditional pottery making', 'images/traditions/bihar-pottery.jpg', 'craft');

-- Insert sample admin user (password: admin123)
INSERT INTO users (full_name, email, password, role) VALUES 
('Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample regular user (password: user123)
INSERT INTO users (full_name, email, password) VALUES 
('Test User', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample contact messages
INSERT INTO contact_messages (user_id, name, email, subject, message) VALUES
(1, 'Admin User', 'admin@example.com', 'Test Message', 'This is a test message from admin'),
(2, 'Test User', 'user@example.com', 'User Query', 'This is a test message from regular user');

