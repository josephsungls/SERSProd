<template>
    <div class="container-fluid">
        <div class="text-center my-3">
            <h1>Account Registration</h1>
            
        </div>
        <div class="row tabs">
            <p :class="tabs.smu? 'active': ''" @click="toggleTab('smu')">I'm from SMU!</p>
            <p :class="tabs.external? 'active': ''" @click="toggleTab('external')">I'm an external instructor!</p>
        </div>
        <div v-if="tabs.smu">
            <SMURegister/>
        </div>
        <div v-else>
            <ExternalRegister/>
        </div>
    </div>
</template>
<script>
    import SMURegister from './SMURegister'
    import ExternalRegister from './ExternalRegister'
    export default{
        data(){
            return {
                tabs:{
                        smu: true,
                        external: false,
                        
                },
                courses: [],
                filtered_courses: []
            }
        },
        components:{
            SMURegister,
            ExternalRegister
        },
        methods:{
            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },

        },
    }
</script>
<style scoped>
</style>