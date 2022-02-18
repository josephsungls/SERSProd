<template>
    <div class = "container-fluid display-container">
        <div class="table-container">
            <div class="table-header">
                <h4>Ongoing and Upcoming</h4>
                <p class = "text-muted">Keep track of your confirmed classes.</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Venue</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken</th>
                        <th scope = "col">Status</th>
                        <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course in upcoming_runs" :key="course">
                        <!-- {{course}} -->
                        <td  @click="selectedRun(course)">
                            <b>
                                {{course.RunCourse_ID}} 
                                <span class="badge badge-danger" v-if="issitToday(course.Run_StartDate)">
                                    Today!
                                </span>
                            </b> 
                            <br> 
                            {{course.Course_Name}} - Class:{{course.Class}}
                        </td> 
                        <td @click="selectedRun(course)"><br> {{course.Run_Venue}}</td>
                        <td @click="selectedRun(course)"><br> {{course.Run_StartDate}}, {{course.Run_StartTime}}</td>
                        <td @click="selectedRun(course)"><br> {{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                        <td><br> 
                            <span :class="['badge', 'badge-pill', 'tool', course.Run_Status, 'more']"  @click="changeCurrent(course)" data-toggle="modal" data-target="#updateStatus" data-tip="Course Ended?" tabindex="1">
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

            <!-- Change Status Modal -->
            <div class="modal fade" id="updateStatus" tabindex="-1" role="dialog" aria-labelledby="updateStatus" aria-hidden="true" data-backdrop="false">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" v-if="current!=[]">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_ID}} - {{current.Course_Name}}</h5>
                            <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><b>Course Description:</b></p>
                            <p>{{current.Course_Desc}}</p>  
                            <br/>
                            <p><b>Change Status:</b></p>
                            <button type ="button" class="btn btn-danger"  @click="courseEnded()" data-dismiss="modal">Course Ended</button>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../../router'
    import {GETUPCOMINGRUNS_URL, CHANGERUNSTATUS_URL} from '../../../variables'
    export default{
        name:'YourClasses',
        data(){
            return{
                upcoming_runs: [],
                current: []
            }
        },
        methods: {
            getUpcomingRuns(){
                
                axios.get(GETUPCOMINGRUNS_URL,
                {withCredentials: true},
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
                    }
                    if (response.data === 'updated'){
                        this.update_values = {...this.new_values}
                        Swal.fire({
                        icon: 'success',
                        title: 'Indicated',
                        text: 'The coordinator can now open the course up for registration ',
                        showConfirmButton: false,
                        timer: 1500
                        })
                        this.goBack()
                    }
                    this.upcoming_runs = response.data
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
            changeStatus(status){
                axios.post(CHANGERUNSTATUS_URL,{
                    'course': this.current,
                    'status': status
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
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
                    }else if (response.data === 'updated'){
                        Swal.fire(
                            'Updated!',
                            'The class has ended!',
                            'success'
                        )
                        this.getUpcomingRuns()
                    }
                    

                })
            },
            selectedRun(course){
                
                this.$parent.selectedRun = course
                this.$parent.enterCourseAdmission()
            },
            courseEnded(){
                Swal.fire({
                    title: 'Course ended?',
                    html: "By indicating that the course has ended, students with the status 'accepted' will have their completion status marked as 'completed'. If their status is 'accepted' and are not present, please change it to 'dropped' before proceeding! <br/> <br/> <b>You won't be able to revert this!</b>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Confirm'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.changeStatus('ended')

                }
                })
            }
        },
        mounted(){
            this.getUpcomingRuns()
        }

    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
        transition: 0.2s;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    .more:hover{
        transform: translateY(-2px);
    }
</style>