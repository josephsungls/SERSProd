<template>
    <footer class = "container-fluid">

            <p class = "align-self-center">SCIS Enrichment @ 2021</p>
            <ul class = "align-self-center">
                <li><a href = "#">About</a></li>
                <li><a href = "#">FAQ</a></li>
                <li><a href = "#">Terms & Conditions</a></li>
                <li><a href = "#">Privacy Policy</a></li>
            </ul>

    </footer>
</template>
<script>
</script>
<style scoped>
    footer{
        bottom:0;
        z-index: 100;
        position: fixed;
        width: 100%;
        background-color: white;
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        color: var(--light-gray);
        padding: 8px 100px;
        font-size: 12px;
        
        /* bottom: 0; */
        background-color: white;
        overflow:auto;
        margin-top: auto;
    }

    ul, p{
        display: flex;
        list-style-type:none;
        margin-bottom: 0px;
    }

    ul li {
        margin-left: 15px;
        transition: 0.2s;

    }
    ul li:hover{
        transform: translateY(-2px);
        color: var(--font-color);
    }
    a {
        text-decoration: none;
        color: inherit;
     
    }
    @media screen and (max-width: 768px ){
        footer{
            padding: 0px 10px;

        }
        p {
            margin-bottom: 0;
        }

    }
    @media screen and (max-width: 420px ){
    footer{
        height: calc(var(--footer-height));

    }


}
</style>