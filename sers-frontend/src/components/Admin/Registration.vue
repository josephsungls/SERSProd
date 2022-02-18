<template>
    <div class = "container-fluid display-container">
        <div class="row sub-tabs tabs" v-if="!individual && !admission && !runFeedback">
                <p :class="tabs.pending? 'active': ''" @click="toggleTab('pending')">Pending</p>
                <p :class="tabs.ongoing? 'active': ''" @click="toggleTab('ongoing')">Ongoing</p>
                <p :class="tabs.offered? 'active': ''" @click="toggleTab('offered')">Offered</p>
                <p :class="tabs.closed? 'active': ''" @click="toggleTab('closed')">Closed</p>
        </div>
        <div class="table-top">

            <div v-if="tabs.pending">
                <h4>Pending</h4>
                <p class = "text-muted">Pending for instructors to update the relevant run details.</p>
            </div>
            <div v-if="tabs.ongoing">
                <h4>Ongoing</h4>
                <p class = "text-muted">Ongoing courses open for registration.</p>
            </div>
            <div v-if="tabs.offered">
                <h4>Offered</h4>
                <p class = "text-muted">Courses opened for interest indication or registration that are offered or have ended.</p>
            </div>             
            <div v-if="tabs.closed">
                <h4>Closed</h4>
                <p class = "text-muted">Courses opened for interest indication that are not offered.</p>
            </div>            
        </div>

        <div v-if="tabs.pending" >
            <PendingRegistration v-if="$store.state.role == 2"/>
        </div>
        <div v-if="tabs.ongoing" >
            <OngoingRegistration/>
        </div>
        <div v-if="tabs.offered" >
            <OfferedRegistration/>
        </div>
        <div v-if="tabs.closed" >
            <ClosedRegistration/>
        </div>
        <div v-if="individual" >
            <IndividualCourse :selectedCourse="current"/>
        </div>
        <div v-if="admission" >
            <CourseAdmission :selectedCourse="current"/>
        </div>
        <div v-if="runFeedback" >
            <RunFeedback :selectedRun="current"/>
        </div>
    </div>
</template>

<script>

    import PendingRegistration from './PendingRegistration'
    import OngoingRegistration from './OngoingRegistration'
    import OfferedRegistration from './OfferedRegistration'
    import ClosedRegistration from './ClosedRegistration'
    import IndividualCourse from './IndividualCourse'
    import CourseAdmission from './CourseAdmission'
    import RunFeedback from '../Instructor/RunFeedback'
    
    export default {
        name: "Registration",
        components:{
            PendingRegistration,
            IndividualCourse,
            OngoingRegistration,
            OfferedRegistration,
            ClosedRegistration,
            CourseAdmission,
            RunFeedback
        },
        data(){
            return {
                registrations: [],
                registered: {},
                current: {},
                tabs:{
                    pending:true,
                    ongoing: false,
                    offered: false,
                    closed: false
                },
                saved_tabs: {},
                individual: false,
                admission: false,
                runFeedback: false
            }
        },
        methods:{

            toggleTab(tab){
                for (const property in this.tabs){
                    property == tab ? this.tabs[property] = true : this.tabs[property] = false
                }
            },
            individualCourse(){
                this.individual = true
                this.saved_tabs = {...this.tabs}
                Object.keys(this.tabs).forEach(v => this.tabs[v] = false)
            },
            courseAdmission(){
                this.admission = true
                this.saved_tabs = {...this.tabs}
                Object.keys(this.tabs).forEach(v => this.tabs[v] = false)
            },
            viewFeedback(){
                this.admission = false
                this.runFeedback = true
            },
            back(){
                if (this.runFeedback){
                    this.runFeedback = false
                    this.admission = true
                }else{
                    this.tabs = {...this.saved_tabs}
                    this.individual = false
                    this.admission = false
                }
            }

        },
    }
</script>
<style scoped>


    .row{
        margin-top: 110px;
        display: flex;
        justify-content: center;
        background-color: rgba(78, 47, 49, 0.767);
        color: white;
        border-radius: 10px;
    }

    .row p:hover {
        color: rgba(70,35,38,255);
    }

    .tabs .active {
        color: rgba(78, 47, 49, 0.767);
    }

    .active {
        border-radius: 10px;
    }

    .sub-tabs{
        margin: 0;
        vertical-align: center;
    }

    .table-top{
        display:inline-block;
        position: relative;
    }


</style>