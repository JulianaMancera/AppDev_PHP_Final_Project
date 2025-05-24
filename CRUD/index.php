<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OJT Application Portal - Welcome</title>
    <!-- React and ReactDOM -->
    <script src="https://cdn.jsdelivr.net/npm/react@18.2.0/umd/react.development.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@18.2.0/umd/react-dom.development.js"></script>
    <!-- Babel for JSX -->
    <script src="https://cdn.jsdelivr.net/npm/@babel/standalone@7.22.9/babel.min.js"></script>
    <!-- Bootstrap CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('bg/city.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 100%;
            width: 100%;
        }
        @media (min-width: 780px) {
            .card {
                max-width: 1000px; /* Slightly larger max width for larger screens */
            }
        }
    </style>
</head>
<body>
    <div id="root"></div>
    <script type="text/babel">
        function LandingPage() {
            return (
                <div className="container my-4">
                    <div className="card shadow-lg p-4 bg-white bg-opacity-95 rounded-3">
                        <header className="text-center mb-4">
                            <h1 className="h3 fw-bold text-primary mb-3">Welcome to the OJT Application Portal</h1>
                            <p className="text-muted">
                               Simplify your On-the-Job Training application record with intuitive platform. 
                            </p>
                        </header>
                        
                        <div className="text-center">
                            <a 
                                href="main.php" 
                                className="btn btn-primary text-uppercase fw-semibold px-4 py-2"
                            >
                                Go to Application Portal
                            </a>
                        </div>

                        <footer className="mt-4 text-center text-muted small">
                            <p>© 2025 Juliana Mancera. All rights reserved.</p>
                        </footer>
                    </div>
                </div>
            );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<LandingPage />);
    </script>
    <!-- Bootstrap JS (Optional, for potential interactivity) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html> 