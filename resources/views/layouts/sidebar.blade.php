<?php
$level = Auth::user()->role; ?>
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
           
            <ul class="nav nav-warning">
                <li class="nav-item {{ isset($m_dashboard) ? $m_dashboard : '' }}">
                    <a href="{{ url('home') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                    <li class="nav-item {{ isset($m_exam) ? $m_exam : '' }}">
                        <a href="{{ url('exam') }}">
                            <i class="fas fa-file-signature"></i>
                            <p>Exam</p>
                        </a>
                    </li>
                @if (in_array($level, [0]))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Quiz Setting</h4>
                    </li>
                    <li class="nav-item {{ isset($m_bank_question) ? $m_bank_question : '' }}">
                        <a href="{{ url('bank_question') }}">
                            <i class="fas fa-folder-open"></i>
                            <p>Bank Questions</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($m_create_exam) ? $m_create_exam : '' }}">
                        <a href="{{ url('create_exam') }}">
                            <i class="fas fa-pencil-alt"></i>
                            <p>Create Exam</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($m_schedule) ? $m_schedule : '' }}">
                        <a href="{{ url('schedule') }}">
                            <i class="far fa-calendar-alt"></i>
                            <p>Schedule</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($m_result) ? $m_result : '' }}">
                        <a href="{{ url('result') }}">
                            <i class="fas fa-chart-bar"></i>
                            <p>Result</p>
                        </a>
                    </li>
                @endif
                @if (in_array($level, [0]))
                    <li class="nav-section">
                        <span class="sidebar-mini-icon">
                            <i class="fa fa-ellipsis-h"></i>
                        </span>
                        <h4 class="text-section">Setting</h4>
                    </li>

                    <li class="nav-item {{ isset($m_user) ? $m_user : '' }}">
                        <a href="{{ url('manage_user') }}">
                            <i class="fas fa-users-cog"></i>
                            <p>Management User</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($m_category) ? $m_category : '' }}">
                        <a href="{{ url('category') }}">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Category</p>
                        </a>
                    </li>
                    <li class="nav-item {{ isset($m_type) ? $m_type : '' }}">
                        <a href="{{ url('type') }}">
                            <i class="fas fa-grip-horizontal"></i>
                            <p>Type</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
