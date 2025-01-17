@extends('frontend.layout.main')
@section('container')
    <style>
        .bg-theme {
            background: #f39f40d4;
        }
    </style>

    {{-- ======================================
                    DOCUMENT TRACKER
    ======================================= --}}
    <div id="document-tracker">
        <div class="container-fluid">
            <div class="tracker-container">
                <div class="row">

                    <div class="col-12"> 
                        <div class="inner-block doc-info-block">
                            <div class="top-block">
                                <div class="title">
                                    {{ $document->document_name }}
                                </div>
                                <div class="buttons">
                                    <button>
                                        <a href="{{ url('notification', $document->id) }}">Send Notification</a>
                                    </button>
                                    <button>
                                        <a href="{{ url('audit-trial', $document->id) }}">Audit Trail</a>
                                    </button>
                                    <button><a href="{{ route('documents.edit', $document->id) }}">Edit</a> </button>
                                    <button>Cancel</button>
                                    <button><a href="{{ url('documents/generatePdf', $document->id) }}">Download</a>
                                    </button>
                                    <button><a href="{{ url('documents/printPDF', $document->id) }}"
                                            target="__blank">Print</a>
                                    </button>
                                    {{-- @if ($document->stage >= 7)
                                        <button data-bs-toggle="modal" data-bs-target="#child-modal">Child</button>
                                    @endif --}}
                                    @if ($document->stage >= 8)
                                    <button type="button" class="btn btn-danger" id="obsolete-button">Obsolete</button>
                                    {{-- <button>Obsolete</button> --}}
                                    <button data-bs-toggle="modal" data-bs-target="#child-modal">Revise</button>
                                    @endif
                                    
                                </div>
                            </div>
                            <div class="bottom-block">
                                <div>
                                    <div class="head">Document Number</div>
                                    <div>
                                        @if($document->revised === 'Yes') 
                                            000{{ $document->revised_doc }}
                                        @else
                                            000{{ $document->id }}
                                        @endif
                                       </div>
                                </div>
                                {{-- <div>
                                    <div class="head">Department</div>
                                    <div>{{ $document->department_name->name }}</div>
                                </div> --}}
                                <div>
                                    <div class="head">Document Type</div>
                                    <div>{{ $document->doc_type->name }}</div>
                                </div>
                                <div>
                                    <div class="head">Working Status</div>
                                    <div>{{ $document->status }}</div>
                                </div>
                                <div>
                                    <div class="head">Last Modified By</div>
                                    @if ($document->last_modify)
                                        <div>{{ $document->last_modify->user_name }}</div>
                                    @else
                                        <div>{{ $document->oreginator->name }}</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="head">Last Modified On</div>
                                    @if ($document->last_modify)
                                        <div>{{ $document->last_modify_date->created_at }}</div>
                                    @else
                                        <div>{{ $document->created_at }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="inner-block tracker">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="main-title">
                                    Record Workflow
                                </div>

                                @if ($document->stage == 1)
                                    <input type="hidden" name="stage_id" value="2" />
                                    <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                        Send For Review<i class="fa-regular fa-paper-plane"></i>
                                    </button>
                                @endif

                                @if ($document->stage == 3)
                                    <input type="hidden" name="stage_id" value="4" />
                                    <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                        Send For Approval<i class="fa-regular fa-paper-plane"></i>
                                    </button>
                                @endif
                                @if ($document->training_required == 'yes')
                                    @if ($document->stage == 5)
                                        <input type="hidden" name="stage_id" value="6" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Training<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                    @if ($document->stage == 7)
                                        <input type="hidden" name="stage_id" value="8" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Effective<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                @elseif($document->training_required == 'no')
                                    @if ($document->stage == 5)
                                        <input type="hidden" name="stage_id" value="8" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Effective<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                @endif


                            </div>
                            <div class="status">
                                <div class="head">Current Status</div>
                                <div class="progress-bars">
                                    @if ($document->stage >= 1)
                                        <div class="active">Draft</div>
                                    @else
                                        <div class="">Draft</div>
                                    @endif
                                    @if ($document->stage >= 2)
                                        <div class="active">In-Review</div>
                                    @else
                                        <div class="">In-Review</div>
                                    @endif
                                    @if ($document->stage == 9)
                                        <div class="active">Rejected</div>
                                    @endif
                                    @if ($document->stage >= 3)      
                                        <div class="active">Reviewed</div>
                                        {{-- && $document->stage < 10 --}}
                                    @else
                                        <div class="">Reviewed</div>
                                    @endif
                                    @if ($document->stage >= 4)            
                                        <div class="active">For-Approval</div>
                                        {{-- && $document->stage < 10 --}}
                                    @else
                                        <div class="">For-Approval</div>
                                    @endif
                                    @if ($document->stage == 10)
                                        <div class="active">Rejected</div>
                                    @endif
                                    @if ($document->stage >= 5)
                                        <div class="active">Approved</div>
                                    @else
                                        <div class="">Approved</div>
                                    @endif
                                    @if ($document->training_required == 'yes')
                                        @if ($document->stage >= 6)
                                            <div class="active">Pending-Traning</div>
                                        @else
                                            <div class="">Pending-Traning</div>
                                        @endif
                                        @if ($document->stage >= 7)
                                            <div class="active">Traning-Complete</div>
                                        @else
                                            <div class="">Traning-Complete</div>
                                        @endif
                                    @endif
                                    @if ($document->stage >= 8)
                                        <div class="active">Effective</div>
                                    @else
                                        <div class="">Effective</div>
                                    @endif
                                    @if ($document->stage == 10)
                                        <div class="active">Obsolete</div>
                                    @else
                                        <div class="">Obsolete</div>
                                    @endif
                                    {{-- <div class="{{ $document->stage == 0 ? 'active' : '' }}">Draft</div>
                                    <div class="{{ $document->stage == 1 ? 'active' : '' }}">Reviewed</div>
                                    <div class="{{ $document->stage == 2 ? 'active' : '' }}">Approved</div>
                                    <div class="{{ $document->stage == 3 ? 'active' : '' }}">Effective</div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div>
                            <div class="inner-block person-table">
                                <div class="main-title mb-0">
                                    Reviewers
                                </div>
                                <button data-bs-toggle="modal" data-bs-target="#doc-reviewers">
                                    View
                                </button>
                            </div>
                            <div class="inner-block person-table">
                                <div class="main-title mb-0">
                                    Approvers
                                </div>
                                <button data-bs-toggle="modal" data-bs-target="#doc-approvers">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="inner-block doc-overview">
                            <div class="main-title">Preview</div>

                            <iframe id="theFrame" width="100%" height="800"
                                src="{{ url('documents/viewpdf/' . $document->id) }}#toolbar=0"></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="doc-reviewers">
        <form action="{{ route('update-doc', $document->id) }}" method="post">
            @csrf
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Reviewers</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->

                    <div class="modal-body">
                        @if ($document->stage <= 2)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="reviewers[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($reviewer))
                                        @foreach ($reviewer as $lan)
                                            <option value="{{ $lan->id }}">
                                                @if ($document->reviewers)
                                                    @php
                                                        $data = explode(',', $document->reviewers);
                                                        $count = count($data);
                                                        $i = 0;
                                                    @endphp
                                                    @for ($i = 0; $i < $count; $i++)
                                                        @if ($data[$i] == $lan->id)
                                                            selected
                                                        @endif
                                                    @endfor
                                                @endif>
                                                {{ $lan->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                        <div class="reviewer-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Reviewers</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Audit Trial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rev_data = explode(',', $document->reviewers);
                                        $i = 0;
                                    @endphp
                                    @for ($i = 0; $i < count($rev_data); $i++)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', $rev_data[$i])
                                                ->first();
                                            $user->department = DB::table('departments')
                                                ->where('id', $user->departmentid)
                                                ->value('name');
                                                $user->status = DB::table('stage_manages')
                                                ->where('user_id', $rev_data[$i])
                                                ->where('document_id', $document->id)
                                                ->where('stage', 'Review-submit') 
                                                ->where('deleted_at', null)                                                
                                                ->latest()
                                                ->first();
                                                $user->statusReject = DB::table('stage_manages')
                                                ->where('user_id', $rev_data[$i])
                                                ->where('document_id', $document->id)
                                                ->where('stage', 'Cancel-by-Reviewer')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->department }}</td>
                                            @if ($user->status)
                                                <td>Reviewed <i class="fa-solid fa-circle-check text-success"></i></td>
                                            @elseif($user->statusReject)
                                                <td>Rejected <i class="fa-solid fa-circle-xmark text-danger"></i></td>
                                            @else
                                                <td>Review Pending</td>
                                            @endif
                                            <td><a
                                                    href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                        type="button">Audit Trial</button></a></td>
                                        </tr>
                                    @endfor

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-header">
                            <h4 class="modal-title">Reviewer Group</h4>
                        </div>

                        @if ($document->stage <= 2)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="reviewers_group[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($reviewergroup))
                                        @foreach ($reviewergroup as $lan)
                                            <option value="{{ $lan->id }}">
                                                @if ($document->reviewers_group)
                                                    @php
                                                        $data = explode(',', $document->reviewers_group);
                                                        $count = count($data);
                                                        $i = 0;
                                                    @endphp
                                                    @for ($i = 0; $i < $count; $i++)
                                                        @if ($data[$i] == $lan->id)
                                                            selected
                                                        @endif
                                                    @endfor
                                                @endif>
                                                {{ $lan->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                        @if ($document->reviewers_group)
                            <div class="reviewer-table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Groups</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rev_data = explode(',', $document->reviewers_group);
                                            $i = 0;
                                        @endphp
                                        @for ($i = 0; $i < count($rev_data); $i++)
                                            @php
                                                $user = DB::table('group_permissions')
                                                    ->where('id', $rev_data[$i])
                                                    ->first();
                                                $user->department = DB::table('role_groups')
                                                    ->where('id', $user->role_id)
                                                    ->value('name');
                                                $users = explode(',', $user->user_ids);

                                                $j = 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div>{{ $user->name }}</div>
                                                    {{-- @if (count($users) > 0)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();
                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->name }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif --}}
                                                </td>

                                                <td>{{ $user->department }}
                                                    @if (count($users) > 1)
                                                        <ul>
                                                            @for ($j = 0; $j < count($users); $j++)
                                                                @php
                                                                    $userdata = DB::table('users')
                                                                        ->where('id', $users[$j])
                                                                        ->first();

                                                                    $userdata->department = DB::table('departments')
                                                                        ->where('id', $userdata->departmentid)
                                                                        ->value('name');
                                                                    $userdata->approval = DB::table('stage_manages')
                                                                        ->where('document_id', $document->id)
                                                                        ->where('user_id', $users[$j])
                                                                        ->latest()
                                                                        ->first();
                                                                @endphp
                                                                <li><small>{{ $userdata->department }}</small></li>
                                                            @endfor

                                                        </ul>
                                                    @endif
                                                </td>
                                                @if ($document->stage >= 3)
                                                    <td>Reviewed <i class="fa-solid fa-circle-check text-success"></i>
                                                        @if (count($users) > 1)
                                                            <ul>
                                                                @for ($j = 0; $j < count($users); $j++)
                                                                    @php
                                                                        $userdata = DB::table('users')
                                                                            ->where('id', $users[$j])
                                                                            ->first();

                                                                        $userdata->department = DB::table('departments')
                                                                            ->where('id', $userdata->departmentid)
                                                                            ->value('name');
                                                                            $userdata->approval = DB::table('stage_manages')
                                                                                ->where('document_id', $document->id)
                                                                                ->where('user_id', $users[$j])
                                                                                ->where('stage', 'Review-Submit')
                                                                                ->where('deleted_at', null)
                                                                                ->latest()
                                                                                ->first();
                                                                                $userdata->reject = DB::table('stage_manages')
                                                                                ->where('document_id', $document->id)
                                                                                ->where('user_id', $users[$j])
                                                                                ->where('stage', 'Cancel-by-reviewer')
                                                                                ->where('deleted_at', null)
                                                                                ->latest()
                                                                                ->first();

                                                                    @endphp
                                                                    @if ($userdata->approval)
                                                                        <li><small>Reviewed <i
                                                                                    class="fa-solid fa-circle-check text-success"></i></small>
                                                                        </li>
                                                                    @elseif($userdata->reject)
                                                                        <li><small>Rejected <i
                                                                                    class="fa-solid fa-circle-xmark text-danger"></i></small>
                                                                        </li>
                                                                    @else
                                                    <td>Review Pending</td>
                                                    <td><a
                                                            href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                                type="button">Audit</button></a></td>

                                                @endif
                                        @endfor

                                        </ul>
                        @endif
                        </td>
                    @else
                        <td>Review Pending</td>
                        @endif
                        </tr>
                        @endfor

                        </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    @if ($document->stage <= 2)
                        <button type="submit">Update</button>
                    @endif
                    <button type="button" data-bs-dismiss="modal">Close</button>
                </div>
        </form>

    </div>
    </div>
    </div>

    <div class="modal fade modal-lg" id="doc-approvers">
        <form action="{{ route('update-doc', $document->id) }}" method="post">
            @csrf
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Approvers</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        @if ($document->stage <= 4)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="approvers[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($approvers))
                                        @foreach ($approvers as $lan)
                                            <option value="{{ $lan->id }}"
                                                @if ($document->reviewers_group) @php
                                   $data = explode(",",$document->approvers);
                                    $count = count($data);
                                    $i=0;
                                @endphp
                                @for ($i = 0; $i < $count; $i++)
                                    @if ($data[$i] == $lan->id)
                                     selected @endif
                                                @endfor
                                        @endif>
                                        {{ $lan->name }}
                                        </option>
                                    @endforeach
                        @endif
                        </select>
                    </div>
                    @endif
                    <div class="reviewer-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Approvers</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Audit Trial</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rev_data = explode(',', $document->approvers);
                                    $i = 0;
                                @endphp
                                @for ($i = 0; $i < count($rev_data); $i++)
                                    @php
                                        $user = DB::table('users')
                                            ->where('id', $rev_data[$i])
                                            ->first();
                                        $user->department = DB::table('departments')
                                            ->where('id', $user->departmentid)
                                            ->value('name');
                                        $user->status = DB::table('stage_manages')
                                            ->where('user_id', $rev_data[$i])
                                            ->where('document_id', $document->id)
                                            ->where('stage', 'Approval-submit')
                                            ->where('deleted_at', null)
                                            ->latest()
                                            ->first();
                                        $user->reject = DB::table('stage_manages')
                                            ->where('user_id', $rev_data[$i])
                                            ->where('document_id', $document->id)
                                            ->where('stage', 'Cancel-by-Approver')
                                            ->where('deleted_at', null)
                                            ->latest()
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->department }}</td>
                                        @if ($user->status)
                                                <td>Approved <i class="fa-solid fa-circle-check text-success"></i></td>
                                            @elseif($user->reject)
                                                <td>Rejected <i class="fa-solid fa-circle-xmark text-danger"></i></td>
                                            @else
                                                <td>Approval Pending</td>
                                            @endif
                                        <td><a
                                                href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                    type="button">Audit</button></a></td>


                                    </tr>
                                @endfor

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title">Approvers Group</h4>
                    </div>
                    @if ($document->stage <= 4)
                        <div class="add-reviewer">
                            <select id="choices-multiple-remove-button" name="approver_group[]"
                                placeholder="Select Reviewers" multiple>
                                @if (!empty($approversgroup))
                                    @foreach ($approversgroup as $lan)
                                        <option value="{{ $lan->id }}"
                                            @if ($document->approver_group) @php
                                   $data = explode(",",$document->approver_group);
                                    $count = count($data);
                                    $i=0;
                                @endphp
                                @for ($i = 0; $i < $count; $i++)
                                    @if ($data[$i] == $lan->id)
                                     selected @endif
                                            @endfor
                                    @endif>
                                    {{ $lan->name }}
                                    </option>
                                @endforeach
                    @endif

                    </select>
                </div>
                @endif
                @if ($document->approver_group)
                    <div class="reviewer-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Groups</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rev_data = explode(',', $document->approver_group);
                                    $i = 0;
                                @endphp
                                @for ($i = 0; $i < count($rev_data); $i++)
                                    @php
                                        $user = DB::table('group_permissions')
                                            ->where('id', $rev_data[$i])
                                            ->first();
                                        $user->department = DB::table('role_groups')
                                            ->where('id', $user->role_id)
                                            ->value('name');
                                        $users = explode(',', $user->user_ids);

                                        $j = 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>{{ $user->name }}</div>
                                            {{-- @if (count($users) > 0)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();

                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->name }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif --}}
                                        </td>

                                        <td>{{ $user->department }}
                                            @if (count($users) > 1)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();
                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->department }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif
                                        </td>
                                        @if ($document->stage >= 5)
                                            <td>Approved <i class="fa-solid fa-circle-check text-success"></i>
                                                @if (count($users) > 1)
                                                    <ul>
                                                        @for ($j = 0; $j < count($users); $j++)
                                                            @php
                                                                $userdata = DB::table('users')
                                                                    ->where('id', $users[$j])
                                                                    ->first();

                                                                $userdata->department = DB::table('departments')
                                                                    ->where('id', $userdata->departmentid)
                                                                    ->value('name');
                                                                    $userdata->approval = DB::table('stage_manages')
                                                                    ->where('document_id', $document->id)
                                                                    ->where('user_id', $users[$j])
                                                                    ->where('stage', 'Approval-Submit')
                                                                    ->where('deleted_at', null)
                                                                    ->latest()
                                                                    ->first();
                                                                $userdata->reject = DB::table('stage_manages')
                                                                    ->where('document_id', $document->id)
                                                                    ->where('user_id', $users[$j])
                                                                    ->where('stage', 'Cancel-by-approver')
                                                                    ->where('deleted_at', null)
                                                                    ->latest()
                                                                    ->first();

                                                            @endphp
                                                            @if ($userdata->approval)
                                                                <li><small>Approved <i
                                                                            class="fa-solid fa-circle-check text-success"></i></small>
                                                                </li>
                                                            @elseif($userdata->reject)
                                                                <li><small>Rejected <i
                                                                            class="fa-solid fa-circle-xmark text-danger"></i></small>
                                                                </li>
                                                            @else
                                            <td>Approval Pending</td>

                                        @endif
                                        <td><a
                                                href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                    type="button">Audit</button></a></td>

                                @endfor

                                </ul>
                @endif
                </td>
            @else
                <td>Approval Pending</td>
                @endif
                <td><a href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                            type="button">Audit</button></a></td>

                </tr>
                @endfor

                </tbody>
                </table>
            </div>
            @endif
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        @if ($document->stage <= 4)
            <button type="submit">Update</button>
        @endif
        <button type="button" data-bs-dismiss="modal">Close</button>
    </div>
    </form>
    </div>
    </div>
    </div>

    <div class="modal fade" id="approve-sign">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>                
                <!-- Modal body -->
                <form action="{{ url('sendforstagechanage') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        {{-- <div class="group-input">
                            <label for="electronic-meaning">Electronic Signature Approved Meaning</label>
                            <select name="electronic-meaning">
                                <option selected>- Please Select -</option>
                                @if ($document->stage == 1)
                                    <option value="assure-approved">Document send for Review</option>
                                @endif
                                @if ($document->stage == 3)
                                    <option value="assure-approved">Document send for Approval</option>
                                @endif
                                @if ($document->stage == 5)
                                    <option value="assure-approved">Document send for Training</option>
                                @endif
                                @if ($document->stage == 7)
                                    <option value="assure-approved">Document Effective</option>
                                @endif
                            </select>
                        </div> --}}
                        <div class="group-input">
                            <label for="username">Username</label>
                            <input type="text" value="{{ old('username') }}" name="username" required>
                            @if ($errors->has('username'))
                                <p class="text-danger">User name not matched</p>
                            @endif
                        </div>
                        <div class="group-input">
                            <label for="password">Password</label>
                            <input type="password" value="{{ old('password') }}" name="password" required>
                            @if ($errors->has('username'))
                                <p class="text-danger">E-signature not matched</p>
                            @endif
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input required name="comment" value="{{ old('comment') }}"/>
                        </div>
                    </div>
                    @if ($document->stage == 1)
                        <input type="hidden" name="stage_id" value="2" />
                    @endif
                    @if ($document->stage == 3)
                        <input type="hidden" name="stage_id" value="4" />
                    @endif
                    @if ($document->training_required == 'yes')
                        @if ($document->stage == 5)
                            <input type="hidden" name="stage_id" value="6" />
                        @endif
                        @if ($document->stage == 7)
                            <input type="hidden" name="stage_id" value="8" />
                        @endif
                    @else
                        @if ($document->stage == 5)
                            <input type="hidden" name="stage_id" value="8" />
                        @endif
                    @endif

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('sendforstagechanage') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="stage_id" value="10" />
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and an outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is the legally binding equivalent of a handwritten signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment"  required>
                        </div> 
                    </div>
    
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="font-weight: 900">Document Revision</h4>
                </div>
                @if($document->revised === 'Yes') 
                 
                <form method="POST" action="{{ url('revision',$document->revised_doc) }}">
            @else
            <form method="POST" action="{{ url('revision',$document->id) }}">
               
            
            @endif
              
                    @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="group-input">
                        <label for="revision">Choose Revision Version</label>
                        <label for="major">
                            Major Version<span  class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#document-management-system-modal"
                            style="font-size: 0.8rem; font-weight: 400;">
                            (Launch Instruction)
                            </span>
                        </label>
                        <input type="number" name="major" id="major" min="0">
                                {{-- <option value="0">-- Select --</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select> --}}
                        
                        <label for="minor">
                            {{-- <input type="radio" name="revision" id="minor"> --}}
                            Minor Version<span  class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#document-management-system-modal-minor"
                            style="font-size: 0.8rem; font-weight: 400;">
                            (Launch Instruction)
                            </span>
                        </label>
                        <input type="number" name="minor" id="minor" min="0" max="9">
                                {{-- <option value="">-- Select --</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select> --}}
                       

                        <label for="reason">
                            Comment
                        </label>
                        <input type="text" name="reason" required>
                    </div>
                  
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" data-bs-dismiss="modal">Close</button>
                    <button type="submit">Submit</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    {{-- CHAT PDF MODAL START --}}
    <button 
    style="position: sticky; bottom: 1rem; left: 95%;"
    type="button" class="btn bg-theme rounded-circle shadow-lg" data-bs-toggle="modal" data-bs-target="#chatModal">
        <img src="{{ asset('user/images/ai_2814666.png') }}" alt="chatWithDoc" class="img-fluid" style="width: 2rem; height: 2.4rem;">
    </button>
    
    <div class="modal border-0" tabindex="-1" id="chatModal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" style="padding: 0 0 0 11px;">
                <div class="row w-100">
                    <div class="col-2 bg-theme d-flex justify-content-center align-items-center text-white" style="border-radius: 0.5rem 0 0;">
                        <i class="fa-solid fa-robot fs-4"></i>
                    </div>
                    <div class="col-9">
                        <h5 class="modal-title fs-6" style="padding: 0.5rem;">
                            Supercharge PDF Conversations: Seamlessly Engage with Your PDFs!</h5>
                    </div>
                </div>
              
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-right: 0.8rem;"></button>
            </div>
            <div class="modal-body">
              <div class="chat-content" id="chatContent" style="height: 15rem; overflow-y: scroll;">
              </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <textarea type="text" name="chatSendMessage" id="" placeholder="Enter your message here" class="form-control shadow-md border-0"></textarea>
                </div>
              <div class="row w-100">
                <div class="col-6">
                    <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Close</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn bg-theme text-white w-100" id="sendChatBtn">
                        <div class="spinner-border spinner-border-sm text-light" role="status" style="display: none">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    {{-- CHAT PDF MODAL END --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
            
        const config = {
            headers: {
                "x-api-key": "sec_qLUcsYBeIWAt564Tk5zhHg76DQHjastL",
                "Content-Type": "application/json",
            },
        };

        function scrollToBottom() {
            var chatContent = $('#chatContent');
            chatContent.animate({
                scrollTop: chatContent.prop('scrollHeight')
            }, 500);
        }

        const data = {
            url: "{{ asset('pdfs/SOP'. $document->id .'.pdf') }}"
        }

        let srcId = "";

        function getUserMessageHtml(message)
        {
            let html = `<div class="chat-content-user-chat bg-theme text-white p-3 rounded-3 w-50 mb-2" style="margin-left: auto;">${message}</div>`;
            return html;
        }

        function getResponseMessageHtml(message)
        {
            let html = `<div class="chat-content-ai-chat bg-light p-3 rounded-3 w-50 mb-2" style="margin-right: auto;">${message}</div>`;
            return html;
        }

        async function initializeChatModal()
        {
            console.log('initializeChatModal')
            try {
                const addPdfUrl = "https://api.chatpdf.com/v1/sources/add-url";

                const res = await axios.post(addPdfUrl, data, config)

                console.log('res', res);

                let srcId = res.data.sourceId;

            } catch (err) {
                console.log('Error in initializeChatModal fn', err.message)
            }
        }

        async function sendChat()
        {

            let message = $('textarea[name=chatSendMessage]').val();

            if (message && message.trim() !== '')
            {
                scrollToBottom()

                $('#sendChatBtn').prop('disabled', true);
                $('#sendChatBtn > i').hide();
                $('#sendChatBtn > div').show();

                const chatData = {
                    "sourceId": srcId,
                    "messages": [
                        {
                            "role": "user",
                            "content": message
                        }
                    ]
                }
    
                const userMsgHtml = getUserMessageHtml(message);
                $('#chatContent').append(userMsgHtml).show('slow');
                $('textarea[name=chatSendMessage]').val('');
    
                try {
    
                    const chatPdfEndpoint = "https://api.chatpdf.com/v1/chats/message";
    
                    const res = await axios.post(chatPdfEndpoint, chatData, config)
    
                    console.log('res', res);
    
                    let resMsg = res.data.content;
    
                    const aiResHtml = getResponseMessageHtml(resMsg);
                    $('#chatContent').append(aiResHtml).show('slow');
    
                } catch (err) {
                    console.log('Error in sendChat fn', err.message)
                }
                $('#sendChatBtn').prop('disabled', false);
                $('#sendChatBtn > i').show();
                $('#sendChatBtn > div').hide();

                scrollToBottom()
            }

            
        }

        initializeChatModal();

        $('#sendChatBtn').click(function() {
            sendChat();
        })

    </script>


<style>
.group-input input {
width: 60%;
}
</style>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var pdfObject = document.querySelector('iframe#theFrame"]');
            var pdfDocument = pdfObject.contentDocument;
            var firstPage = pdfDocument.querySelector('.page:first-of-type');
            firstPage.style.display = 'none';
        });
    </script>
    <script>
        // JavaScript to open modal when obsolete button is clicked
        document.getElementById('obsolete-button').addEventListener('click', function() {
            $('#signature-modal').modal('show');
        });
    </script>
@endsection
