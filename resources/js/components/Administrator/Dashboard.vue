<template>
	<div>
		<div class="section">

			<div class="columns is-centered">
				<div class="column is-8">

					<div class="columns">
						<div class="column">
							<div class="box">
								<div class="box-header">
									TODAY SALES
								</div>
								<div class="sales-no">
									{{ todaySales | formatToCurrency }}
								</div>
							</div>
						</div>

						<div class="column">
							<div class="box">
								<div class="box-header">
									SALES (DATE RANGE)
								</div>
								<div class="sales-no">
									{{ rangeDateSales }}
								</div>
							</div>
						</div>
					</div>

				</div><!--col-->
			</div><!--cols-->

		</div> <!--section-->

		
	</div>
</template>

<script>
export default {
	data(){
		return{
			todaySales: 0,
			rangeDateSales: 0
		}
	},
	
	methods: {
		loadTodaySales(){
			axios.get('/load-today-sales').then(res=>{
				this.todaySales = res.data[0].today_sales
			})
		}
	},

	mounted(){
		this.loadTodaySales()
	}
}
</script>

<style>

	.sales-no{
		font-weight: bold;
		font-size: 5em;
		text-align: center;
	}
 /* .hero{
    background-image: url("/img/bg-hero.jpg");
    background-repeat: no-repeat;
    background-size: cover;
}  */
</style>