import sqlite3
import csv
from datetime import datetime
import tkinter as tk
from tkinter import filedialog, messagebox, Toplevel
from tkinter import ttk
from PIL import Image, ImageTk
import os

# مسیر دیتابیس
db_path = "charity_database.db"

def initialize_database():
    conn = sqlite3.connect(db_path)
    c = conn.cursor()

    c.execute('''CREATE TABLE IF NOT EXISTS families (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        case_number TEXT,
        registration_date TEXT,
        category TEXT,
        head_name TEXT,
        father_name TEXT,
        grandfather_name TEXT,
        birth_place TEXT,
        age INTEGER,
        tazkira_number TEXT,
        education_level TEXT,
        marital_status TEXT,
        health_status TEXT,
        job TEXT,
        income REAL,
        address TEXT,
        phone TEXT,
        photo_path TEXT
    )''')

    c.execute('''CREATE TABLE IF NOT EXISTS members (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        family_id INTEGER,
        name TEXT,
        father_name TEXT,
        grandfather_name TEXT,
        birth_place TEXT,
        age INTEGER,
        tazkira_number TEXT,
        education_level TEXT,
        marital_status TEXT,
        health_status TEXT,
        job TEXT,
        income REAL,
        photo_path TEXT,
        FOREIGN KEY (family_id) REFERENCES families(id) ON DELETE CASCADE
    )''')

    conn.commit()
    conn.close()

initialize_database()

root = tk.Tk()
root.title("ثبت مشخصات خانواده - مؤسسه خیریه سیدالشهدا")
root.geometry("1000x750")
root.configure(bg="#E3F2FD")
root.resizable(False, False)

style = ttk.Style()
try:
    style.theme_use("clam")
except tk.TclError:
    pass

icon_img = Image.new("RGB", (16, 16), "#4CAF50")
root.iconphoto(False, ImageTk.PhotoImage(icon_img))
FARSI_FONT = ("Bahij Nazanin", 13)
TITLE_FONT = ("Bahij Titr", 18, "bold")

title = tk.Label(
    root,
    text="فرم ثبت خانواده – مؤسسه خیریه سیدالشهدا",
    font=TITLE_FONT,
    bg="#E3F2FD",
    fg="#0D47A1",
    pady=12,
)
title.pack(fill="x")

form_frame = tk.Frame(root, bg="#FFFFFF", padx=20, pady=20)
form_frame.pack(fill="both", expand=True, padx=30, pady=10)

form_data = {
    "case_number": tk.StringVar(value=f"PR-{datetime.now().strftime('%Y%m%d%H%M%S')}"),
    "registration_date": tk.StringVar(value=datetime.now().strftime("%Y-%m-%d")),
    "category": tk.StringVar(value="عام"),
    "head_name": tk.StringVar(),
    "father_name": tk.StringVar(),
    "grandfather_name": tk.StringVar(),
    "birth_place": tk.StringVar(),
    "age": tk.IntVar(),
    "tazkira_number": tk.StringVar(),
    "education_level": tk.StringVar(),
    "marital_status": tk.StringVar(),
    "health_status": tk.StringVar(),
    "job": tk.StringVar(),
    "income": tk.DoubleVar(),
    "address": tk.StringVar(),
    "phone": tk.StringVar(),
    "photo_path": tk.StringVar()
}

def upload_image():
    file_path = filedialog.askopenfilename(filetypes=[("Image Files", "*.png;*.jpg;*.jpeg")])
    if file_path:
        form_data["photo_path"].set(file_path)
        img = Image.open(file_path).resize((100, 120))
        img = ImageTk.PhotoImage(img)
        image_label.config(image=img)
        image_label.image = img

def save_record():
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    try:
        c.execute('''
            INSERT INTO families (
                case_number, registration_date, category, head_name, father_name, grandfather_name,
                birth_place, age, tazkira_number, education_level, marital_status,
                health_status, job, income, address, phone, photo_path
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ''', tuple(var.get() for var in form_data.values()))
        conn.commit()
        family_id = c.lastrowid
        messagebox.showinfo("ذخیره شد", "سرپرست ذخیره شد. حالا اعضای خانواده را وارد نمایید.")
        open_members_form(family_id)
    except Exception as e:
        messagebox.showerror("خطا", f"خطا در ذخیره اطلاعات:\n{e}")
    finally:
        conn.close()

def export_to_csv():
    """Export all family records to a CSV file."""
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute("SELECT * FROM families")
    families = c.fetchall()
    if not families:
        messagebox.showinfo("خطا", "هیچ داده‌ای برای خروجی وجود ندارد.")
        conn.close()
        return

    file_path = filedialog.asksaveasfilename(
        defaultextension=".csv",
        filetypes=[("CSV Files", "*.csv")]
    )
    if not file_path:
        conn.close()
        return

    headers = [desc[0] for desc in c.description] + ["members"]
    with open(file_path, "w", newline="", encoding="utf-8") as csvfile:
        writer = csv.writer(csvfile)
        writer.writerow(headers)
        for fam in families:
            c.execute("SELECT name FROM members WHERE family_id=?", (fam[0],))
            member_names = [m[0] for m in c.fetchall()]
            writer.writerow(list(fam) + [";".join(member_names)])
    conn.close()
    messagebox.showinfo("ذخیره شد", "خروجی با موفقیت ذخیره شد.")

labels = [
    ("شماره پرونده:", "case_number"),
    ("تاریخ ثبت:", "registration_date"),
    ("دسته‌بندی:", "category"),
    ("نام سرپرست:", "head_name"),
    ("نام پدر:", "father_name"),
    ("نام پدربزرگ:", "grandfather_name"),
    ("محل تولد:", "birth_place"),
    ("سن:", "age"),
    ("شماره تذکره:", "tazkira_number"),
    ("سطح تحصیل:", "education_level"),
    ("وضعیت تأهل:", "marital_status"),
    ("وضعیت صحی:", "health_status"),
    ("وظیفه:", "job"),
    ("عاید ماهانه:", "income"),
    ("آدرس:", "address"),
    ("شماره تماس:", "phone"),
]

for idx, (label_text, key) in enumerate(labels):
    row = idx // 2
    col = (idx % 2) * 2
    tk.Label(form_frame, text=label_text, font=FARSI_FONT, bg="#FFFFFF").grid(row=row, column=col, sticky="e", pady=4, padx=4)
    tk.Entry(form_frame, textvariable=form_data[key], font=FARSI_FONT, width=30).grid(row=row, column=col+1, sticky="w", pady=4, padx=4)

image_frame = tk.Frame(form_frame, bg="#E3F2FD", width=140)
image_frame.grid(row=0, column=4, rowspan=9, padx=10, sticky="n")

default_img = Image.new('RGB', (100, 120), color='#CFD8DC')
default_img = ImageTk.PhotoImage(default_img)
image_label = tk.Label(image_frame, image=default_img, width=100, height=120, bg="#CFD8DC")
image_label.image = default_img
image_label.pack(pady=5)


upload_btn = tk.Button(
    image_frame,
    text="آپلود عکس",
    command=upload_image,
    font=FARSI_FONT,
    bg="#00ACC1",
    fg="white",
    activebackground="#00838F",
    relief="raised",
    bd=3,
    padx=10,
)
upload_btn.pack(pady=10)

# Frame to hold action buttons in a single row
action_frame = tk.Frame(root, bg="#E3F2FD")
action_frame.pack(pady=20)

save_btn = tk.Button(
    action_frame,
    text="ذخیره اطلاعات",
    command=save_record,
    font=FARSI_FONT,
    bg="#4CAF50",
    fg="white",
    activebackground="#2E7D32",
    relief="raised",
    bd=4,
    padx=30,
    pady=10,
)
save_btn.pack(side="left", padx=10)
export_btn = tk.Button(
    root,
    text="خروجی به CSV",
    command=export_to_csv,
    font=FARSI_FONT,
    bg="#2196F3",
    fg="white",
    activebackground="#1976D2",
    relief="raised",
    bd=4,
    padx=30,
    pady=10,
)
export_btn.pack(pady=(0, 20))

def open_family_list():
    """Open a window displaying all saved families with search."""
    win = Toplevel(root)
    win.title("لیست خانواده‌ها")
    win.geometry("900x600")
    win.configure(bg="#F1F8E9")

    search_var = tk.StringVar()
    search_frame = tk.Frame(win, bg="#F1F8E9")
    search_frame.pack(fill="x", pady=10)
    tk.Label(search_frame, text="جستجو:", font=FARSI_FONT, bg="#F1F8E9").pack(side="right", padx=5)
    search_entry = tk.Entry(search_frame, textvariable=search_var, font=FARSI_FONT, width=30)
    search_entry.pack(side="right", padx=5)

    cols = ("case", "head", "phone", "reg_date")
    tree = ttk.Treeview(win, columns=cols, show="headings", selectmode="browse")
    tree.heading("case", text="شماره پرونده")
    tree.heading("head", text="نام سرپرست")
    tree.heading("phone", text="تلفن")
    tree.heading("reg_date", text="تاریخ ثبت")
    tree.column("case", anchor="center", width=120)
    tree.column("head", anchor="center", width=160)
    tree.column("phone", anchor="center", width=120)
    tree.column("reg_date", anchor="center", width=120)
    scrollbar = ttk.Scrollbar(win, orient="vertical", command=tree.yview)
    tree.configure(yscrollcommand=scrollbar.set)
    tree.pack(side="left", fill="both", expand=True, padx=(10,0), pady=10)
    scrollbar.pack(side="left", fill="y", pady=10)

    def load_data(query=""):
        conn = sqlite3.connect(db_path)
        c = conn.cursor()
        wildcard = f"%{query}%"
        c.execute(
            """SELECT case_number, head_name, phone, registration_date
            FROM families
            WHERE head_name LIKE ? OR case_number LIKE ? OR phone LIKE ?""",
            (wildcard, wildcard, wildcard),
        )
        rows = c.fetchall()
        conn.close()

        tree.delete(*tree.get_children())
        for row in rows:
            tree.insert("", "end", values=row)

    load_data()

    def on_search(*_):
        load_data(search_var.get())

    search_entry.bind("<KeyRelease>", on_search)

    close_btn = tk.Button(
        win,
        text="بستن",
        command=win.destroy,
        font=FARSI_FONT,
        bg="#EF5350",
        fg="white",
        activebackground="#E53935",
        relief="raised",
        bd=3,
        padx=20,
        pady=5,
    )
    close_btn.pack(pady=10)

def open_help_list():
    """Open a window showing supervisors for assistance distribution."""
    win = Toplevel(root)
    win.title("لیست کمک")
    win.geometry("900x550")
    win.configure(bg="#F5F5F5")

    frame = tk.Frame(win, bg="#F5F5F5")
    frame.pack(fill="both", expand=True, padx=10, pady=10)

    style = ttk.Style(win)
    style.configure("Help.Treeview", rowheight=46)

    cols = ("sign", "phone", "count", "father", "head", "num")
    tree = ttk.Treeview(frame, columns=cols, show="headings", style="Help.Treeview")
    headers = {
        "num": "شماره",
        "head": "اسم سرپرست",
        "father": "اسم پدر",
        "count": "تعداد فامیل",
        "phone": "نمبر تماس",
        "sign": "امضا",
    }
    for col in cols:
        tree.heading(col, text=headers[col])
        tree.column(col, anchor="center", width=120, stretch=True)

    vsb = ttk.Scrollbar(frame, orient="vertical", command=tree.yview)
    hsb = ttk.Scrollbar(frame, orient="horizontal", command=tree.xview)
    tree.configure(yscrollcommand=vsb.set, xscrollcommand=hsb.set)
    tree.grid(row=0, column=0, sticky="nsew")
    vsb.grid(row=0, column=1, sticky="ns")
    hsb.grid(row=1, column=0, sticky="ew")
    frame.grid_rowconfigure(0, weight=1)
    frame.grid_columnconfigure(0, weight=1)

    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute("SELECT id, head_name, father_name, phone FROM families")
    families = c.fetchall()
    for idx, (fid, head, father, phone) in enumerate(families, start=1):
        c.execute("SELECT COUNT(*) FROM members WHERE family_id=?", (fid,))
        count = c.fetchone()[0] + 1
        tree.insert("", "end", values=("", phone, count, father, head, idx))
    conn.close()

    def print_list():
        file = filedialog.asksaveasfilename(
            defaultextension=".ps",
            filetypes=[("PostScript", "*.ps")],
        )
        if file:
            win.update()
            win.postscript(file=file)
            try:
                if os.name == "nt":
                    os.startfile(file, "print")
                else:
                    os.system(f"lp '{file}'")
            except Exception:
                messagebox.showinfo("چاپ", f"فایل در {file} ذخیره شد")

    def export_csv():
        file_path = filedialog.asksaveasfilename(
            defaultextension=".csv",
            filetypes=[("CSV Files", "*.csv")],
        )
        if not file_path:
            return
        with open(file_path, "w", newline="", encoding="utf-8") as csvfile:
            writer = csv.writer(csvfile)
            writer.writerow([headers[c] for c in cols])
            for row_id in tree.get_children():
                writer.writerow(tree.item(row_id)["values"])
        messagebox.showinfo("ذخیره شد", "فایل CSV ذخیره شد")

    btn_frame = tk.Frame(win, bg="#F5F5F5")
    btn_frame.pack(fill="x", pady=10)
    print_btn = tk.Button(
        btn_frame,
        text="چاپ",
        command=print_list,
        font=FARSI_FONT,
        bg="#4CAF50",
        fg="white",
        activebackground="#388E3C",
        relief="raised",
        bd=3,
        padx=20,
        pady=5,
    )
    print_btn.pack(side="right", padx=5)

    export_btn = tk.Button(
        btn_frame,
        text="خروجی به CSV",
        command=export_csv,
        font=FARSI_FONT,
        bg="#2196F3",
        fg="white",
        activebackground="#1976D2",
        relief="raised",
        bd=3,
        padx=20,
        pady=5,
    )
    export_btn.pack(side="right", padx=5)
    close_btn = tk.Button(
        btn_frame,
        text="بستن",
        command=win.destroy,
        font=FARSI_FONT,
        bg="#EF5350",
        fg="white",
        activebackground="#E53935",
        relief="raised",
        bd=3,
        padx=20,
        pady=5,
    )
    close_btn.pack(side="right", padx=5)

list_btn = tk.Button(
    root,
    text="مشاهده خانواده‌ها",
    command=open_family_list,
    font=FARSI_FONT,
    bg="#673AB7",
    fg="white",
    activebackground="#512DA8",
    relief="raised",
    bd=4,
    padx=30,
    pady=10,
)
list_btn.pack(pady=(0, 20))

help_btn = tk.Button(
    action_frame,
    text="لیست کمک",
    command=open_help_list,
    font=FARSI_FONT,
    bg="#009688",
    fg="white",
    activebackground="#00796B",
    relief="raised",
    bd=4,
    padx=30,
    pady=10,
)
help_btn.pack(side="left", padx=10)

def open_members_form(family_id):
    member_win = Toplevel(root)
    member_win.title("ثبت اعضای خانواده")
    member_win.geometry("1250x540")
    member_win.configure(bg="#ECEFF1")
    tk.Label(member_win, text="ثبت اعضای خانواده (حداکثر ۴ نفر)", font=TITLE_FONT, bg="#ECEFF1", fg="#1A237E").pack(pady=10)

    container = tk.Frame(member_win, bg="#ECEFF1")
    container.pack(padx=10, pady=10)

    field_labels = [
        "عکس", "عاید", "شغل", "وضعیت صحی", "وضعیت تأهل", "سطح تحصیل", "نمبر تذکره",
        "سن", "محل تولد", "نام پدر کلان", "نام پدر", "نام"
    ]

    education_options = ["بیسواد", "ابتداییه", "فارغ 12", "بکلوریا", "لیسانس"]
    marital_options = ["مجرد", "متاهل", "بیوه", "مطلقه"]

    members_data = []

    for row in range(4):
        row_data = {}
        for col, label_text in enumerate(field_labels):
            tk.Label(container, text=label_text, font=FARSI_FONT, bg="#ECEFF1").grid(row=row*2, column=col, padx=4, pady=2, sticky='e')

            if label_text == "عکس":
                photo_var = tk.StringVar()
                photo_label = tk.Label(container, text="تصویر ندارد", bg="#CFD8DC", width=14, relief="sunken")
                photo_label.grid(row=row*2+1, column=col, padx=4)

                def make_upload_cmd(lbl=photo_label, var=photo_var):
                    def upload():
                        file = filedialog.askopenfilename(filetypes=[("Image Files", "*.png;*.jpg;*.jpeg")])
                        if file:
                            var.set(file)
                            lbl.config(text="عکس انتخاب شد", bg="#A5D6A7")
                    return upload

                upload_btn = tk.Button(container, text="آپلود", command=make_upload_cmd(), font=("Bahij Nazanin", 10))
                upload_btn.grid(row=row*2+1, column=col, sticky="e", padx=2)
                row_data["photo_path"] = photo_var

            elif label_text == "سطح تحصیل":
                edu_var = tk.StringVar()
                dropdown = tk.OptionMenu(container, edu_var, *education_options)
                dropdown.config(font=FARSI_FONT, width=12)
                dropdown.grid(row=row*2+1, column=col)
                row_data["education_level"] = edu_var

            elif label_text == "وضعیت تأهل":
                marital_var = tk.StringVar()
                dropdown = tk.OptionMenu(container, marital_var, *marital_options)
                dropdown.config(font=FARSI_FONT, width=12)
                dropdown.grid(row=row*2+1, column=col)
                row_data["marital_status"] = marital_var

            elif label_text == "وضعیت صحی":
                health_var = tk.StringVar()
                entry = tk.Entry(container, textvariable=health_var, font=FARSI_FONT, width=14)
                entry.insert(0, "سالم")
                entry.grid(row=row*2+1, column=col)
                row_data["health_status"] = health_var

            else:
                entry_var = tk.StringVar()
                entry = tk.Entry(container, textvariable=entry_var, font=FARSI_FONT, width=14)
                entry.grid(row=row*2+1, column=col)
                key = {
                    "نام": "name",
                    "نام پدر": "father_name",
                    "نام پدر کلان": "grandfather_name",
                    "محل تولد": "birth_place",
                    "سن": "age",
                    "نمبر تذکره": "tazkira_number",
                    "شغل": "job",
                    "عاید": "income"
                }[label_text]
                row_data[key] = entry_var

        members_data.append(row_data)

    def save_members():
        conn = sqlite3.connect(db_path)
        c = conn.cursor()
        saved_count = 0
        for row in members_data:
            name = row.get("name").get().strip()
            if name:
                try:
                    c.execute('''
                        INSERT INTO members (
                            family_id, name, father_name, grandfather_name, birth_place,
                            age, tazkira_number, education_level, marital_status,
                            health_status, job, income, photo_path
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ''', (
                        family_id,
                        row.get("name").get(),
                        row.get("father_name").get(),
                        row.get("grandfather_name").get(),
                        row.get("birth_place").get(),
                        int(row.get("age").get() or 0),
                        row.get("tazkira_number").get(),
                        row.get("education_level").get(),
                        row.get("marital_status").get(),
                        row.get("health_status").get(),
                        row.get("job").get(),
                        float(row.get("income").get() or 0),
                        row.get("photo_path").get()
                    ))
                    saved_count += 1
                except Exception as err:
                    messagebox.showerror("خطا در ذخیره", f"خطا در ردیف {saved_count + 1}:\n{err}")
        conn.commit()
        conn.close()
        messagebox.showinfo("ذخیره شد", f"{saved_count} عضو با موفقیت ذخیره شد.")
        member_win.destroy()

    save_btn = tk.Button(member_win, text="ذخیره اعضا", font=FARSI_FONT,
                         bg="#4CAF50", fg="white", command=save_members,
                         padx=30, pady=6)
    save_btn.pack(pady=15)

# اجرای نهایی
root.mainloop()
