<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        /* Header */
        .header {
            position: sticky;
            top: 0;
            width: 100%;
            background-color: #212529;
            color: white;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header .toggle-btn {
            font-size: 1.5rem;
            cursor: pointer;
            color: #ffc107;
        }

        .header .profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .header .profile a {
            color: #ffc107;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #212529;
            color: white;
            transform: translateX(-100%);
            z-index: 1050;
            transition: transform 0.3s ease-in-out;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar.open {
            transform: translateX(0);
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            border-bottom: 1px solid #343a40;
            display: flex;
            align-items: center;
        }

        .sidebar ul li a {
            color: #ffc107;
            text-decoration: none;
            width: 100%;
        }

        .sidebar ul li a:hover {
            color: white;
            background-color: #495057;
            border-radius: 5px;
        }

        .sidebar ul li i {
            margin-right: 15px;
        }

        /* Overlay Effect */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            display: none;
        }

        .overlay.show {
            display: block;
        }

        /* Main Content */
        .main-content {
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .card .card-body {
            display: flex;
            align-items: center;
        }

        .card-icon {
            font-size: 2rem;
        }

        .card h5 {
            margin: 0;
            font-size: 1rem;
            color: #6c757d;
        }

        .card h3 {
            margin: 0;
            font-size: 1.5rem;
        }
    </style>
</head>