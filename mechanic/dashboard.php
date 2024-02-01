<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Mechanic Dashboard</title>
	<style type="text/css">
		.body1{
			max-width: 1400px;
			border: 1px solid black;
			overflow: hidden;
		}
		.side{
			width: 300px;
			height: 700px;
			border: 1px solid black;
			float: left;
		}
		.header{
			width: calc(100% - 300px);
			height: 200px;
			float: right;
			border: 1px solid black;
		}
		.card{
			width: 200px;
			height: 150px;
			border: 1px solid black;
			margin-left: 10%;
			margin-top: 2%;
			float: left;
		}
		.logout{
			width: 200px;
			height: 50px;
			border: 1px solid black;
			margin-top: 10px; /* Adjust margin for better visibility */
		}
		.table1{
			width: calc(100% - 300px);
			height: 450px;
			float: right;
			border: 1px solid black;
			margin-top: 2%;
		}

		/* Media Query for smaller screens */
		@media screen and (max-width: 768px) {
			.body1 {
				max-width: 100%;
			}
			.side {
				width: 100%;
				height: auto;
				float: none;
			}
			.header {
				width: 90%;
				float: none;
				margin-top: 20px; /* Adjust margin for better visibility */
			}
			.table1 {
				width: 90%;
				float: none;
				margin-top: 20px; /* Adjust margin for better visibility */
			}
		}
	</style>
</head>
<body>
	<div class="body1">
		<div class="side">
			<ul>
				<li>Dashboard</li>
				<li>Mechanics</li>
				<li>users</li>
				<li>requests</li>
				<li>Reports</li>
			</ul>
			<div class="logout">logout</div>
		</div>
		<div class="header">
			<div class="card"></div>
			<div class="card"></div>
			<div class="card"></div>
		</div>
		<div class="table1"></div>
	</div>
</body>
</html>
