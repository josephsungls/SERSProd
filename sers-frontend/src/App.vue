<template>
	<div id="page-container">
			<Navigation />
		
		<div class="content">
			<router-view />
		</div>
		<Footer />
	</div>
</template>

<script>
	import Navigation from "./components/Navigation";
	import Footer from "./components/Footer";


	export default {
		name: "App",
		components: {
			Navigation,
			Footer,
			},
		mounted(){
			
		},
		created(){
			this.$store.dispatch('setRole')
		}
	};
</script>

<style>
	@import url("https://fonts.googleapis.com/css2?family=Nunito&display=swap");
	@import url("https://fonts.googleapis.com/css2?family=Source+Serif+Pro&display=swap");

	:root {
		--navigation-color: #ffffff;
		--body-text: "Nunito", sans-serif;
		--title-text: "Source Serif Pro", serif;
		--light-gray: #A8A8A8;
		--button-color: #55a3ff;
		--button-color-hover: #117cff;
		--nav-height: 6em;
		--footer-height: 5em;
		--font-color: black;
		--body-color: #f3f3f3;
		--lighter-gray: #F8F8F8;
		--dark-gray: #616161;
		--pill-green: #29CC97;
		--pill-blue: #384DBC;
		--pill-yellow: #FFB800;
		--pill-orange: #EC8628;
		--pill-red: #EC2828;
		--maroon: #972E54;
		--purple: #384DBC;
		--orange: #F24040;
		font-family: var(--body-text);
		--tool-tip-speed: 0.2s;
	}
	body{
		/* background: linear-gradient(180deg, #FFFFFF 0%, rgba(255, 254, 216, 0.856) 100%); */
		background: #fffff280;
		/* background-image: url("assets/sandpaperbg.jpg"); */
	}
	.content{
		min-height: calc(100vh - var(--nav-height) - var(--footer-height)); 
		margin-top:5em;
		
	}

    .tabs{
        margin: 0 auto 2em auto;
        background-color: var(--lighter-gray);
        width: fit-content;
        border-radius: 8px;
        display: flex;
        flex-direction: row;
        justify-content: center;
         
    }
    
    .tabs p{
        padding: 5px 35px;
        font-weight: bold;
        border-radius: 10px;
        margin: 5px;
        transition:0.2s;
        

    }
    .tabs p:hover{
        cursor:pointer;
        background-color: var(--navigation-color);
    }
    .tabs .active{
        color: var(--button-color);
        background-color: var(--navigation-color);
    }

	::-webkit-scrollbar-track
	{
		border-radius: 10px;
		
	}

	::-webkit-scrollbar
	{
		border-radius: 10px;
		width: 4px;
		height: 4px;
		-webkit-box-shadow: inset 0 0 3px rgba(255,255,255,0);
		background-color: rgba(0,0,0,0);
	}

	::-webkit-scrollbar-thumb
	{
		border-radius: 10px;
		-webkit-box-shadow: inset 0 0 3px rgba(255,255,255,0);
		background-color: var(--button-color);
	}
	.display-container{
		width: 80%;
	}
	
    .table-container{
        margin: auto;
        background-color: white;
        border: 1px solid var(--light-gray);
        border-radius: 10px;
        width: 100%;
        height: 30vh;
        overflow:auto;
        min-height:30vh;
        max-height: 70vh;
        height: fit-content;
		margin-bottom:5em;
    }
	
    .table-header{
        margin:1em;
    }
/*== start of code for tooltips ==*/
.tool {
    /* cursor: help; */
    position: relative;
}

.report-table{
	margin-bottom: 0;
}
.report-table-btn{
	float: right;
	width: fit-content;
	font-size: 15px;
	border-radius: 5px;
	color: white;
	background-color: var(--purple);
	margin-top: 1em;
	border: 0px;
	margin-bottom: 3em;
	transition:0.2s;
	padding: 3px 15px;
}
.report-insights-btn{
	padding: 3px 15px;
	width: fit-content;
	font-size: 15px;
	border-radius: 5px;
	color: white;
	background-color: var(--purple);
	border: 0px;
	margin-top: 3em;
	transition:0.2s;
}
.preview {
	margin-top: 0em;
}
.report-table-btn:hover,
.report-insights-btn:hover{
	transform: translateY(-2px)
}
@media (max-width: 760px) {
  .tool::after { 
        font-size: .75em;
        margin-left: -5em;
        width: 10em; 
  }
}
</style>
