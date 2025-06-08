import tkinter as tk
from tkinter import ttk, messagebox
from datetime import datetime
from . import db


class LoginWindow(tk.Tk):
    def __init__(self):
        super().__init__()
        self.title('Seyed Alshohada Welfare Institute - Login')
        self.geometry('400x250')
        self.configure(padx=20, pady=20)

        ttk.Label(self, text='Username:').grid(row=0, column=0, sticky='e', pady=5)
        ttk.Label(self, text='Password:').grid(row=1, column=0, sticky='e', pady=5)

        self.username_entry = ttk.Entry(self)
        self.password_entry = ttk.Entry(self, show='*')
        self.username_entry.grid(row=0, column=1, pady=5)
        self.password_entry.grid(row=1, column=1, pady=5)

        ttk.Button(self, text='Login', command=self.login).grid(row=2, column=0, columnspan=2, pady=20)

    def login(self):
        username = self.username_entry.get().strip()
        password = self.password_entry.get().strip()

        conn = db.get_connection()
        cur = conn.cursor()
        cur.execute('SELECT * FROM users WHERE username=? AND password=?', (username, password))
        user = cur.fetchone()
        conn.close()

        if user:
            self.destroy()
            app = MainApp(user)
            app.mainloop()
        else:
            messagebox.showerror('Error', 'Invalid credentials')


class MainApp(tk.Tk):
    def __init__(self, user):
        super().__init__()
        self.title('Seyed Alshohada Welfare Institute')
        self.geometry('800x600')
        self.user = user
        self.configure(padx=20, pady=20)

        ttk.Label(self, text='ثبت نام').pack(pady=10)
        container = ttk.Frame(self)
        container.pack(fill='both', expand=True)

        self.fields = {}
        field_defs = [
            ('full_name', 'نام کامل'),
            ('father_name', 'نام پدر'),
            ('grandfather_name', 'نام پدرکلان'),
            ('birth_place', 'محل تولد'),
            ('age', 'سن'),
            ('tazkira_number', 'نمبر تذکره'),
            ('education_level', 'سویه تعلیمی'),
            ('marital_status', 'حالت مدنی'),
            ('health_status', 'وضعیت صحی'),
            ('job', 'وظیفه'),
            ('monthly_income', 'عاید ماهانه'),
            ('home_address', 'آدرس منزل'),
            ('phone_number', 'شماره تماس'),
        ]

        for i, (key, label) in enumerate(field_defs):
            ttk.Label(container, text=label).grid(row=i, column=0, sticky='e', pady=2)
            entry = ttk.Entry(container)
            entry.grid(row=i, column=1, sticky='w', pady=2)
            self.fields[key] = entry

        ttk.Button(self, text='Save', command=self.save_record).pack(pady=20)

    def save_record(self):
        data = {k: e.get().strip() for k, e in self.fields.items()}
        data['case_number'] = f"CASE-{datetime.now().strftime('%Y%m%d%H%M%S')}"
        data['registration_date'] = datetime.now().strftime('%Y-%m-%d')
        data['categories'] = ''

        conn = db.get_connection()
        cur = conn.cursor()
        cur.execute(
            """
            INSERT INTO registrations (
                full_name, father_name, grandfather_name, birth_place, age, tazkira_number,
                education_level, marital_status, health_status, job, monthly_income,
                home_address, phone_number, case_number, registration_date, categories
            ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            """,
            (
                data['full_name'], data['father_name'], data['grandfather_name'], data['birth_place'],
                data['age'], data['tazkira_number'], data['education_level'], data['marital_status'],
                data['health_status'], data['job'], data['monthly_income'], data['home_address'],
                data['phone_number'], data['case_number'], data['registration_date'], data['categories']
            )
        )
        conn.commit()
        conn.close()
        messagebox.showinfo('Success', 'Record saved.')
        for e in self.fields.values():
            e.delete(0, tk.END)


if __name__ == '__main__':
    db.init_db()
    login = LoginWindow()
    login.mainloop()
