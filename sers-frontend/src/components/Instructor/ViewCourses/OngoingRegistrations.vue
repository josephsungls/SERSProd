<template>
    <div class = "container-fluid display-container" v-if="access">
        <div class="table-container">
            <div class="table-header">
                <h4>Ongoing Registrations</h4>
                <p class = "text-muted">Classes assigned to you that are open for student registrations</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Instructor(s)</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken</th>
                        <th scope = "col">Deadline</th>
                        
                        <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                    </tr>
                </thead>
                <tbody v-if="ongoingRegistrations.length > 0">
                    <tr v-for="course in ongoingRegistrations" :key="course">
                        <!-- {{course}} -->
                        <td>
                            <b>
                                {{course.Course_ID}} 
                            </b> 
                            <br> 
                            {{course.Course_Name}} <span v-if="course.Class">- Class:{{course.Class}}</span>
                        </td>
                        <td v-if="course.User_Name"><br> {{processInstructor(course.User_Name)}}</td>
                        <td v-else> - </td>
                        <td>
                            {{course.Run_StartDate}}
                            <br> 
                            <small class="text-muted">{{course.Run_StartTime}}</small>
                        </td>
                        <td v-if="course.Run_MaxSlots">{{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                        <td>
                            {{course.Registration_End}}
                        </td>
                        
                       
                    </tr>
                </tbody>
            </table>


        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import Swal from 'sweetalert2'
    import router from '../../../router'
    import {GETINSTRUCTORONGOINGREGISTRATIONS_URL} from '../../../variables'
    export default{
        name:'OngoingRegistrations',
        data(){
            return{
                ongoingRegistrations: {},
                access: false
            }
        },
        methods: {
            getRegistrations(){
                
                axios.get(GETINSTRUCTORONGOINGREGISTRATIONS_URL,
                {withCredentials: true}
                ).then(response => {    
                    if (response.data != 'no rights'){
                        this.access = true
                        this.ongoingRegistrations = response.data
                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: 'You do not have access to this information',
                            showConfirmButton: false,
                            timer: 1000
                        })
                        router.push('/')
                    }
                    
                })
            },
            processInstructor(instructors){
                if (!instructors){
                    return instructors
                }
                return instructors.split(',').join(', ')
            }
        },
        mounted(){
            this.getRegistrations()
        }

    }
</script>
<style scoped>

</style>