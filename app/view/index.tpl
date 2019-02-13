<!DOCTYPE html>
<html>
<head>
	<title>hello easy</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
	<div class="contanier">
			<div class="jumbotron">
				<h1>welcome to our own php world!</h1>
				<h1>有很多改进的地方，让我们大家一起创造一个属于我们团队自己的轻框架！</h1>
			</div>
			

	</div>
	<div class="container">
		<table class="table">
					

		{foreach $data as $v}
			<tr>
				<td><h3>{$v['userid']}</h3></td>
			</tr>
				
		{/foreach}


		</table>
		


			<ul class="pagination">
			{$page}
		</ul>
	</div>
		


</body>
</html>