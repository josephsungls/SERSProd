<template>
    <div class = "container-fluid display-container">
        <div class="table-container">
            <div class="table-header">
                <h4>Class History</h4>
                <p class="text-muted">Review classes you have delivered.</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Venue</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken</th>
                        <th scope = "col">Feedback Submitted</th>
                        <th scope = "col">Status</th>
                        <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in upcoming_runs" :key="course" @click="viewFeedbacks(course)">
                        <!-- {{course}} -->
                        <td>
                            <b>
                                {{course.RunCourse_ID}} 
                                <span class="badge badge-danger" v-if="issitToday(course.Run_StartDate)">
                                    Today!
                                </span>
                            </b> 
                            <br> 
                            {{course.Course_Name}} - Class:{{course.Class}}
                        </td>
                        <td><br> {{course.Run_Venue}}</td>
                        <td><br> {{course.Run_StartDate}}, {{course.Run_StartTime}}</td>
                        <td><br> {{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                        <td><br> {{course.Feedback_Count? course.Feedback_Count : '0'}}/{{course.Run_MaxSlots}}</td>
                        <td><br> 
                            <span :class="['badge', 'badge-pill', course.Run_Status, 'more']"  @click="changeCurrent(course)">
                                {{course.Run_Status}}
                            </span>
                        </td>
                        <!-- <td class = "text-center">
                            <br> <span :class="['badge', 'badge-pill', course.Run_Status== 'pending'? 'gray':'updated']">Open</span>
                        </td>
                        -->
                    </tr>
                </tbody>
            </table>

        </div>
        
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../router'
    import {GETENDEDRUNS_URL} from '../../variables'
    export default{
        name:'ClassHistoryComponent',
        data(){
            return{
                upcoming_runs: [],
                current: []
            }
        },
        methods: {
            getEndedRuns(){
                // console.log(REGISTRATIONS_URL)
                axios.get(GETENDEDRUNS_URL,
                {   
                    withCredentials:true
                }
                ).then(response => {
                    
                    if (response.data === 'no rights'){
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
                    }else{
                        this.upcoming_runs = response.data
                    }
                    
                })
            },

            issitToday(Run_StartDate){
                var today = new Date()
                var dd = today.getDate()
                var mm = today.getMonth() + 1
                var yyyy = today.getFullYear()

                if (dd<10){
                    dd = '0'+ dd
                }
                if (mm<10){
                    mm = '0' + mm
                }

                today = [yyyy,mm,dd].join('-')
                console.log(today == Run_StartDate)
                return today == Run_StartDate
            },
            changeCurrent(course){
                this.current = course
            },
            viewFeedbacks(course){
                this.$parent.selectRun(course)
            },
        },
        mounted(){
            this.getEndedRuns()
        }

    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
        transition: 0.2s;
    }
    :deep(.ended){
        background-color: var(--pill-red);
        color: white;
    }
    .more:hover{
        transform: translateY(-2px);
    }
</style>