# URL Shortener — Multi-Company Role-Based System

A Laravel-based URL Shortener service supporting multiple companies, user roles (SuperAdmin, Admin, Member), user invitations, and public short URL redirection.

---

## 🚀 Features

### **Authentication & Roles**
- User roles: **SuperAdmin, Admin, Member**
- Login / Logout / Registration (Laravel Breeze or Jetstream)
- Authorization using custom `role:` middleware

### **Company Management**
- Only **SuperAdmin** can create, edit, delete companies
- List of companies visible only to SuperAdmin

### **Invitation System**
- **SuperAdmin** can invite **Admin**
- **Admin** can invite **Admin or Member** within the same company
- Invited user automatically gets:
  - Role (Admin / Member)
  - Company ID

### **URL Shortener**
- **Admin & Member** can create short URLs
- **SuperAdmin cannot create short URLs**
- **Admin** can view all short URLs belonging to their company
- **Member** can view only URLs created by themselves
- Public redirect route: http://localhost/t2p-projects/url-shortener/public/s/{code} → redirects to original URL

### **Tests (Feature Tests)**
Covers:
- Role-based access
- Admin/Member short URL creation
- SuperAdmin restriction
- URL listing rules
- Invitation workflow
- Public redirect

---

## 🛠 Tech Stack

- Laravel 11  
- MySQL  
- Laravel Breeze 
- PHPUnit for testing  

---

## 📦 Installation & Setup

- Download zip file 
- Composer update command  `Composer update`
- Run command `npm install && npm run build`
- Database import file [ file location is db_files folder ]

## SuperAdmin login:
email:  `superAdmin@admin.com`
password:  `password`

- Admin & Member invitation create default password is : `password123`

## 💡 Acceptable AI Usage Note

Tool : ChatGPT
- create test case 
- some command 
- create readme file