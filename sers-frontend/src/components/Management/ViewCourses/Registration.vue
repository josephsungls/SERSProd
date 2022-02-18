<template>
    <div class = "container-fluid  display-container">
        <div class="row sub-tabs tabs" v-if="!individual && !admission">
                <p :class="tabs.ongoing? 'active': ''" @click="toggleTab('ongoing')">Ongoing</p>
                <p :class="tabs.ended? 'active': ''" @click="toggleTab('ended')">Ended</p>
        </div>

        <div v-if="tabs.ongoing">
            <OngoingRegistration/>
        </div>
        <div v-if="tabs.ended">
            <EndedRegistration/>
        </div>
    </div>
</template>
<script>

    import OngoingRegistration from './RegistrationTabs/OngoingRegistration'
    import EndedRegistration from './RegistrationTabs/EndedRegistration'
    export default {
        name: "Registration",
        components:{
            // PendingRegistration,
            // IndividualCourse,
            OngoingRegistration,
            EndedRegistration
            // CourseAdmission
        },
        data(){
            return {
    
                tabs:{
                    ongoing: true,
                    ended: false
                },
            }
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
    .sub-tabs{
        margin: 0;
    }


</style>