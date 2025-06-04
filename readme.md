# 🛒 Sibantu

PHP-based multi-role web application that connects **Users**, **Sellers**, and **Admins** on one platform. It includes dashboards, payment handling, authentication, FAQs, and more.

---

## 📁 Project Structure

### 🌐 Main Files
- **index.php** – Main dashboard and homepage of the platform.
- **auth.php** – Handles user login and registration (frontend).
- **catalog.php** – Lists all services ("jasa") offered by sellers.
- **payment.php** & **order.php** – Frontend handling for payment and order processing.
- **faq.php** – Frequently Asked Questions page.
- **form.php** – For submitting custom user requests.
- **forgotpassword.php** – Page for users to reset their password.
- **aboutus.php** – Information about the platform.
- **404.php** – Custom 404 Not Found error page.

---

## 📂 Folder Overview

#### 📂 `database/`
Contains all backend logic and database-related scripts.

#### 📂 `User/` – _Created by **Zidan**_
Everything related to **User** role:
- User dashboard
- Profile
- Order history
- Request statuses

#### 📂 `Seller/` – _Created by **Yazied**_
Everything related to **Seller** role:
- Seller dashboard
- Order management
- Service listings
- Earnings overview

#### 📂 `ADMIN/` – _Created by **Rafi**_
The **Admin Panel**:
- System controls
- User & seller management
- Platform analytics
- Service moderation

#### 📂 `assets/`
Static assets such as:
- CSS files
- JavaScript
- Images
- Fonts  
Used primarily by `index.php` and other frontend files.

---