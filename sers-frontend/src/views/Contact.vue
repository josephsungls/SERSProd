<template>
    <div>
        <div class = "container header">
            <div class="row">
                <h1>Have some Questions?</h1>
                
            </div>
            <div class="row tabs">
                <p :class="faq? 'active': ''" @click="toggleTab('faq')">FAQ</p>
                <p :class="ask? 'active': ''" @click="toggleTab('ask')">Ask Us</p>
            </div>
        </div>
        <div v-if = "faq" class="animate__animated animate__fadeIn animate__faster">
            <Faq/>
        </div>
        <div class="animate__animated animate__fadeIn animate__faster" v-else>
            <ContactForm/>
        </div>
    </div>
</template>
<script>
    import ContactForm from '../components/ContactForm'
    import Faq from '../components/Faq'
    export default{
        name: "Contact",
        components: {
            ContactForm,
            Faq,
        },
        data(){
            return{
                faq: true,
                ask: false
            }
        },
        methods:{
            toggleTab(tab){
                if(tab == 'faq'){
                    this.faq = true
                    this.ask = false
                }else{
                    this.faq = false
                    this.ask = true
                }
            }
        }
    }
</script>
<style scoped>
    .row{
        display: flex;
        justify-content: center;

    }
    .row h1{
        text-align: center;
        font-family: var(--title-text);
        padding: 20px;
        padding-top: 1em;
        font-weight: bold;
        padding-bottom: 0;
    }

</style>