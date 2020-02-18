<?php
//fetch.php
$link = mysqli_connect("localhost","root","","vitaplus-db");
if(mysqli_connect_errno()){
	echo "Failed to connect:".mysqli_connect_errno();
}
$output = '';
if(isset($_POST["query"]))
{
	$search = mysqli_real_escape_string($link, $_POST["query"]);
	$query = "	 
	SELECT * FROM products WHERE name LIKE '%".$search."%' AND (status='published' || status='sale') AND stocks > 0 ORDER BY name ASC LIMIT 3";
	$result = mysqli_query($link, $query);
	if(mysqli_num_rows($result) > 0)
	{
		$output .= ' ';
		for($i=0; $i<$num_rows=mysqli_fetch_array($result);$i++) {
			$idproduct=$num_rows["ID"];
			$product=$num_rows["name"];
			// $description=$num_rows["description"];
			$stock=$num_rows["stocks"];
			$price=$num_rows["price"];

			?>
			<table class="table table bordered">
				<tr>
					<td class="col-md-8"><span class="text-black"><?=$product;?></span><br><span style="color: #ee4266;"> ₱ <?=$price;?></span></td>
					<td>					
						<form class="form-inline" method="POST" action="">
							<div class="form-group">								                    
								<button type="submit" class="btn btn-primary add-to-cart primary-green"><span class="icon-shopping-bag"></span> Add</button>
							</div>
							<input type="hidden" value="<?php echo $idproduct; ?>" name="product-id">
							<input type="hidden" value="1" name="add-to-cart">
						</form>
					</td>      
				</tr>
			</table>

                    <?php
                }
            }
            else
            {
            	echo 'Item not found';
            }
        }
        ?>

