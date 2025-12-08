# Laravel 12 – Dummy Data with Factories & Seeders

A Laravel 12 project that demonstrates how to generate **dummy data** using **Factories and Seeders**.  
Includes Products, Tags, Multiple Images, Soft Deletes, and Pivot Table relationships.

---

## 📌 Features

✅ Laravel 12 setup  
✅ Factory-based dummy data generation  
✅ Database seeders  
✅ Products with:
- Multiple images
- Tags (Many-to-Many relationship)
- Soft Deletes

✅ Clean and beginner-friendly structure

---

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Database**: MySQL / SQLite
- **ORM**: Eloquent
- **Faker**: For generating fake data

---

## ⚙️ Installation Steps

### 1. Clone the repository

```bash
git clone https://github.com/your-username/laravel-dummy-data.git
cd laravel-dummy-data
2. Install dependencies
bash
Copy code
composer install
npm install
3. Create .env file
bash
Copy code
cp .env.example .env
Update database credentials inside .env:

env
Copy code
DB_DATABASE=your_database_name
DB_USERNAME=root
DB_PASSWORD=
4. Generate Application Key
bash
Copy code
php artisan key:generate
5. Run Migrations
bash
Copy code
php artisan migrate
6. Run Seeders
bash
Copy code
php artisan db:seed
Or run everything at once:

bash
Copy code
php artisan migrate:fresh --seed
🗂️ Database Structure
Tables Included:
products

tags

product_images

product_tag (pivot table)

🧪 Sample Data
After running the seeders:

50 Products will be created

Each product will have:

1–5 images

1–4 tags
