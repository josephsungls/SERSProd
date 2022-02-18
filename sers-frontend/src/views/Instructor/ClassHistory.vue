<template>
<!-- This view is for instructor, admin and management staff -->
    <div class = "container-fluid" v-if="access">

        <div v-if="history">
            <ClassHistoryComponent/>
        </div>
        <div v-if="courseAdmission">
            <CourseAdmission :selectedCourse="selectedRun"/>
        </div>
        <div v-if="feedback">
            <RunFeedback :selectedRun="selectedRun"/>
        </div>
    </div>

</template>
<script>
    import ClassHistoryComponent from '../../components/Instructor/ClassHistory'
    import RunFeedback from '../../components/Instructor/RunFeedback.vue'
    import CourseAdmission from '../../components/Admin/CourseAdmission.vue'
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import {GETUSERROLE_URL} from '../../variables.js'
    import router from '../../router.js'
    // import {COURSES_URL, ONGOINGCAMPAIGN_URL, GETUPCOMINGRUNS_URL} from '../../variables'
    export default{
        name: 'ClassHistory',
        data(){
            return {
                history: true,
                feedback: false,
                courseAdmission: false,
                selectedRun: [],
                access: false
            }
        },
        components:{
            ClassHistoryComponent,
            RunFeedback,
            CourseAdmission
        },
        methods:{
            selectRun(course){
                this.selectedRun = course
                this.courseAdmission = true
                this.history = false
                this.feedback = false
            },
            viewFeedback(){
                this.courseAdmission = false
                this.history = false
                this.feedback = true
            },
            back(){
                if(this.feedback){
                    this.courseAdmission = true
                    this.history = false
                    this.feedback = false     
                }else{
                    this.courseAdmission = false
                    this.history = true
                    this.feedback = false    
                }
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 3){
                        this.redirectToCourse()
                        

                    }else{
                        this.access = true
                        
                    }
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 1000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },
        },
        mounted(){
            this.checkUserRole()
        },
        computed:{
    
        }

    }
</script>
<style scoped>
    .row{
        display: flex;
        justify-content: center;
        
    }

    .container-fluid{
        margin-top: 7em;
    }

</style>