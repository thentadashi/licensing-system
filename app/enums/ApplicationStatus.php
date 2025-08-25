<?php
namespace App\Enums;

enum ApplicationStatus: string {
    case Pending = 'Pending';
    case Submitted = 'Submitted';
    case UnderReview = 'Under Review';
    case RevisionRequested = 'Revision Requested';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Trashed = 'Trashed';
}

enum ProgressStage: string {
    case Submitted = 'Submitted';
    case UnderReview = 'Under Review';
    case ProcessingLicense = 'Processing License';
    case ReadyForRelease = 'Ready for Release';
    case Completed = 'Completed';
    case RevisionRequest = 'Revision request';
    case Rejected = 'Rejected';
    case Trashed = 'Trashed';
}
