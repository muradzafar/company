import sqlite3
from pathlib import Path

DB_PATH = Path(__file__).resolve().parent / 'welfare.db'


def get_connection():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn


def init_db():
    conn = get_connection()
    cur = conn.cursor()

    # Users table
    cur.execute(
        """
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT UNIQUE NOT NULL,
            password TEXT NOT NULL,
            role TEXT DEFAULT 'user'
        )
        """
    )

    # Registrations table
    cur.execute(
        """
        CREATE TABLE IF NOT EXISTS registrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            full_name TEXT NOT NULL,
            father_name TEXT,
            grandfather_name TEXT,
            birth_place TEXT,
            age INTEGER,
            tazkira_number TEXT,
            education_level TEXT,
            marital_status TEXT,
            health_status TEXT,
            job TEXT,
            monthly_income REAL,
            home_address TEXT,
            phone_number TEXT,
            case_number TEXT,
            registration_date TEXT,
            categories TEXT
        )
        """
    )

    # Income and expense table
    cur.execute(
        """
        CREATE TABLE IF NOT EXISTS finance (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type TEXT NOT NULL, -- income or expense
            amount REAL NOT NULL,
            description TEXT,
            date TEXT NOT NULL
        )
        """
    )

    conn.commit()
    conn.close()


if __name__ == '__main__':
    init_db()
