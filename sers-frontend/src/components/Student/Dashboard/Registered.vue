<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Registered Runs</h4>
                <p class = "text-muted">Courses which you have registered for.</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Class Date</th>
                        <th scope = "col">Registration Status</th>
                        <!-- <th scope = "col">Completion Status</th> -->
                    </tr>
                </thead>
                <tbody  v-if="filtered_courses.length != 0" >
                    
                    <tr v-for="course in filtered_courses" :key="course" @click="changeStatus(course)" data-toggle="modal" data-target="#exampleModalCenterTitle">
                        <td><b>{{course.Course_Name}} - {{course.Class}}</b> <br> {{course.Run_Desc}}</td>
                        <td><p><b>{{course.Run_StartDate}}</b> <br/> {{course.Run_StartTime}}</p></td>
                        <td>
                            <span :class="['badge', 'badge-pill' , course.Reg_Status == 'pending' && course.Run_Status == 'ongoing' ? 'ongoing':  course.Reg_Status == 'pending' && course.Run_Status == 'offered' ? 'pending' :  course.Reg_Status == 'pending' && course.Run_Status == 'closed' ? 'closed' :  course.Reg_Status ]">

                                <span>{{course.Reg_Status == 'pending' && course.Run_Status == 'ongoing' ? 'ongoing':  course.Reg_Status == 'pending' && course.Run_Status == 'offered' ? 'pending' :  course.Reg_Status == 'pending' && course.Run_Status == 'closed' ? 'closed' :  course.Reg_Status}}</span>

                            </span>
                        </td>
                        <!-- <td>
                            <span :class="['badge', 'badge-pill' , course.Completion_Status]">

                                <span>{{course.Completion_Status}}</span>

                            </span>
                        </td> -->
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <div class="table-header"><h4>No courses registered</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenterTitle" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="false"  v-if="filtered_courses.length != 0" >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{current.Course_Name}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{current.Course_Desc}}</p>
                        <p v-if="current.Run_Status == 'ended'">Course has ended</p>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-if="current.Reg_Status == 'pending' && current.Run_Status == 'offered'" @click="dropRegistrationWarning(current)">Drop</button>
                        <button type ="button" class="btn btn-danger"  data-dismiss="modal" v-else-if="current.Reg_Status == 'pending' && current.Run_Status == 'ongoing'" @click="deregister(current)">Deregister</button>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
// INDICATEDINTEREST_URL
// ONGOINGCAMPAIGN_URL
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import {REGISTEREDCOURSES_URL, DEREGISTER_URL, DROPREGISTRATION_URL} from '../../../variables'
    export default {
        name: "Registered",
        data(){
            return {
                filtered_courses: [],
                indicated: {},
                current: {}
            }
        },
        methods:{
           getRegisteredCourse(){
                    axios.get(REGISTEREDCOURSES_URL,
                    {
                        // params: {'user_id': this.$store.state.user_id},
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        console.log(response.data)
                        this.filtered_courses = response.data
                        
                    })

            },
            changeStatus(course){
                this.current = course
            },
            check(){
                if (this.$store.state.user_id){
                    this.getRegisteredCourse()
                }else{
                    setTimeout(this.check, 100)
                }
            },
            deregister(course){
                axios.post(DEREGISTER_URL,{
                    // 'user_id': this.$store.state.user_id,
                    'course_id': course.Course_ID,
                    'run_start': course.RRun_StartDate,
                    'class_no': course.Class

                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data == 'removed'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully deregistered',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getRegisteredCourse()
                    }
                    

                })
            },
            dropRegistration(course){
            
                axios.post(DROPREGISTRATION_URL,{
                    'course_id': course.Course_ID,
                    'run_start': course.RRun_StartDate,
                    'class_no': course.Class

                },{
                    withCredentials: true
                }).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    
                    if (response.data == 'dropped'){
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully dropped',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        this.getRegisteredCourse()
                    }

                })
            
            },
            dropRegistrationWarning(){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Dropping the course at this stage is a irreversible action.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, drop it!'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.dropRegistration(this.current)
                }
                })
            }

        },
        mounted(){
            this.check()
        }
    }
</script>
<style scoped>

    .badge-pill{
        width: 100px;
    }
    :deep(.ongoing){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.closed){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.pending),
    :deep(.not.completed),
    :deep(.dropped){
        background-color: var(--gray);
        color: white;
    }
    :deep(.completed){
        background-color: var(--pill-green);
        color: white;
    }
</style>