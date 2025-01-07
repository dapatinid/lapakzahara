
    <style>
        body {
	font-family: 'Poppins', sans-serif;
	background-color: var(--semi-grey);
}

        #loading-container {
            text-align: center;
        }

        #logo1 {
            width: 100px; /* Sesuaikan ukuran logo */
            height: auto;
        }

        #loading-text {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .loading-spinner {
            border: 4px solid #3498db;
            border-radius: 50%;
            border-top: 4px solid #e74c3c;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
<div id="loading-container">
    <div class="loading-spinner"></div>
</div>
