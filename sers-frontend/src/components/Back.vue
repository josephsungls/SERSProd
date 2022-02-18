<template>
    <span class='back'>
        <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
    </span>
</template>
<script>
    export default{
        name: "Back",
        methods:{
            goBack(){
                return this.$router.go(-1)
            }
        }
    }
</script>
<style scoped>
    .back button{
        background-color: var(--navigation-color);
        border: none;
        font-size: 2em;
        transition:0.2s;
    }
    .back button:hover{
        color: var(--button-color);
    }
    .back button:focus{
        outline:none;
        box-shadow:none;
    }
</style>