<template>
    <div class="table-container">
        <div class="table-header">
            <h4>Course Database</h4>
            <p class = "text-muted">Create and update courses.</p>
        </div>
        <table class = "table">
            <thead>
                <tr>
                    <th scope = "col">Course Code & Name</th>
                    <th scope = "col">Course Desc</th>
                    <th scope = "col">Instructor</th>
                    <th scope = "col">Slots Taken</th>
                    <th scope = "col">Registration End Date</th>
                    <th scope = "col" class = "status text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="course in registrations" :key="course">
                    <td><b>{{course.Course_ID}}</b> <br> {{course.Course_Name}} - Class:{{course.Class}}</td>
                    <td>  <br> {{course.Run_Desc}}</td>
                    <td> <br> {{processInstructors(course.User_Name)}}</td>
                    <td> <br> {{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                    <td> <br>
                        <span class="badge badge-pill updated" data-toggle="modal" data-target="#changeRegEnd"> 
                            {{course.Registration_End}}
                        </span>
                    </td>
                    <td class = "text-center">
                        <br> 
                        <span class="badge badge-pill badge-warning"  data-toggle="modal" data-target="#changeStatus"> 
                            {{course.Run_Status}}
                       </span>
                    </td>
                    
                </tr>
            </tbody>
        </table>
        <!-- Change Date Modal
        <div class="modal fade" id="changeRegEnd" tabindex="-1" role="dialog" aria-labelledby="changeRegEnd" aria-hidden="true" data-backdrop="false">
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
                        <form>
                            <label for="start">Registration End Date:</label>
                            <input type="date" id="end"
                                v-model="registration_end">
                        </form>
                        <br/>
                        <button type ="button" class="btn btn-success mr-2"  @click="changeDate()" data-dismiss="modal" v-if="current.FCourse_Status != 'offered'">Change Date</button>
                        
                    </div>
                </div> 
            </div>
        </div> -->
        <!-- Change Status Modal -->
        <!-- <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="changeStatus" aria-hidden="true" data-backdrop="false">
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
                        <button type ="button" class="btn btn-success mr-2"  @click="changeStatus('offered')" data-dismiss="modal">Offer</button>
                        <button type ="button" class="btn btn-danger mr-2"  @click="changeStatus('closed')" data-dismiss="modal">Close</button>
                    </div>
                </div> 
            </div>
        </div> -->
    </div>

</template>
<script>
    import axios from 'axios'
    import {ENDEDREGISTRATION_URL} from '../../../../variables'
    export default{
        name: 'EndedRegistrations',
        data(){
            return {
                current: [],
                registrations: [],
                registration_end: ''
            }
        },
        methods:{
            getEndedRegistrations(){
                axios.get(ENDEDREGISTRATION_URL).then(response => {
                    this.registrations = response.data
                    if (response.data.length > 0){
                        
                        this.current = this.registrations[0],
                        this.registration_end = this.current.Registration_End
                    }
                    
                })
        
            },
            processInstructors(instructors){
                if (!instructors){
                    return instructors
                }
                return instructors.split(',').join(', ')
            }
        },
        mounted(){
            this.getEndedRegistrations()

        }
        
    }
</script>
<style scoped>
    .badge-pill{
        width: 100px;
    }
    .badge{
        transition:0.2s;
    }
    .badge:hover{
        transform: translateY(-3px);
    }
    :deep(.pending){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.updated){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.gray){
        background-color: var(--gray);
        color: white;
    }
    .badge-warning{
        color:white;
    }
    .instructor-pill{
        width:200px;
        /* font-size:5px; */
    }
    td {
        transition:0.1s;
        cursor: pointer;
    }
    tr:hover > td{
        background-color:var(--button-color);
        color:var(--navigation-color);
    }
</style>