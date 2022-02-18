<template>
    <div class = "container-fluid" v-if="access">

        <div v-if="runTable">
            <RunTable :runs="runs"/>
        </div>
        <div v-if="courseAdmission">
            <CourseAdmission :selectedCourse="selectedRun"/>
        </div>
        <div v-if="runFeedback">
            <RunFeedback :selectedRun="selectedRun"/>
        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router'
    import RunTable from '../../components/Management/CourseHistory/RunTable'
    // same view as instructor as functionality is same
    import RunFeedback from '../../components/Instructor/RunFeedback'
    import CourseAdmission from '../../components/Admin/CourseAdmission.vue'
    import {GETCOURSERUNS_URL, GETUSERROLE_URL} from '../../variables.js'
    export default{
        name: "CourseHistory",
        components:{
            RunTable,
            RunFeedback,
            CourseAdmission
        },
        data(){
            return {
                runs: [],
                selectedRun: [],
                runTable: true,
                runFeedback: false,
                courseAdmission: false,
                access: false
            }
        },

        methods:{
            getCourseRuns(){
                axios.get(GETCOURSERUNS_URL, {
                    withCredentials: true,
                    params:{
                        'course_id': this.$route.params.course_id
                    }
                }).then(response => {
                    this.runs = response.data
                })  
            },
            selectRun(course){
                this.selectedRun = course
                this.runTable = false
                this.runFeedback = false
                this.courseAdmission = true
            },
            viewFeedback(){
                this.courseAdmission = false
                this.runTable = false
                this.runFeedback = true
            },
            back(){
                if(this.runFeedback){
                    this.courseAdmission = true
                    this.runTable = false
                    this.runFeedback = false     
                }else{
                    this.courseAdmission = false
                    this.runTable = true
                    this.runFeedback = false    
                }
            },
            checkUserRole(){
                axios.get(GETUSERROLE_URL,
                {withCredentials: true}                
                ).then(response=>{
                    
                    if (response.data != 2 && response.data != 4){
                        this.redirectToCourse()
                    }else{
                        this.access = true
                        this.getCourseRuns()
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
            backToCourse(){
                axios.get(GETUSERROLE_URL, {
                    withCredentials: true,
                }).then(response => {
                    if (response.data === 4){
                        router.push('/managementcourses')   
                    }else if (response.data === 2){
                        router.push('/')
                    }else{
                        router.push('/courses')
                    }

                })  

            },

        },        
        mounted(){
            this.checkUserRole()
        }
    }
</script>
<style scoped>
</style>