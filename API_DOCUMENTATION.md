# Maintenance Requests API Documentation

## Base URL
```
http://127.0.0.1:8000/api
```

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your_token}
```

## Endpoints

### 1. Create Maintenance Request
**POST** `/maintenance-requests`

Creates a new maintenance request with status 'pending'.

#### Request Body
```json
{
    "title": "HVAC System Repair",
    "description": "The air conditioning system is not working properly",
    "company_name": "Advanced Line Technology"
}
```

#### Response (201 Created)
```json
{
    "status": true,
    "message": "Maintenance request submitted successfully",
    "data": {
        "id": 16,
        "title": "HVAC System Repair",
        "company_name": "Advanced Line Technology",
        "requested_by": "John Doe",
        "description": "The air conditioning system is not working properly",
        "status": "pending",
        "created_at": "2025-08-25 12:30:00"
    }
}
```

### 2. Get User's Maintenance Requests
**GET** `/maintenance-requests`

Returns all maintenance requests for the authenticated user.

#### Response (200 OK)
```json
{
    "status": true,
    "message": "Maintenance requests retrieved successfully",
    "data": [
        {
            "id": 16,
            "title": "HVAC System Repair",
            "company_name": "Advanced Line Technology",
            "requested_by": "John Doe",
            "description": "The air conditioning system is not working properly",
            "status": "pending",
            "created_at": "2025-08-25 12:30:00"
        }
    ],
    "total": 1
}
```

### 3. Get All Maintenance Requests (Admin Only)
**GET** `/maintenance-requests/all`

Returns all maintenance requests in the system. Only accessible by building_admin or company_admin.

#### Response (200 OK)
```json
{
    "status": true,
    "message": "All maintenance requests retrieved successfully",
    "data": [
        {
            "id": 16,
            "title": "HVAC System Repair",
            "company_name": "Advanced Line Technology",
            "requested_by": "John Doe",
            "description": "The air conditioning system is not working properly",
            "status": "pending",
            "created_at": "2025-08-25 12:30:00"
        }
    ],
    "total": 7,
    "statistics": {
        "pending": 3,
        "in_progress": 2,
        "completed": 1,
        "rejected": 1
    }
}
```

### 4. Update Request Status (Admin Only)
**PUT** `/maintenance-requests/{id}/status`

Updates the status of a maintenance request. Only accessible by building_admin or company_admin.

#### Request Body
```json
{
    "status": "completed"
}
```

#### Response (200 OK)
```json
{
    "status": true,
    "message": "Request status updated successfully",
    "data": {
        "id": 16,
        "title": "HVAC System Repair",
        "status": "completed",
        "updated_at": "2025-08-25 12:35:00"
    }
}
```

## Status Values
- `pending` - Request is waiting to be processed
- `in_progress` - Request is being worked on
- `completed` - Request has been completed
- `rejected` - Request has been rejected

## Error Responses

### 401 Unauthorized
```json
{
    "status": false,
    "message": "Authentication required to submit maintenance requests",
    "error": "User must be logged in"
}
```

### 403 Forbidden
```json
{
    "status": false,
    "message": "Access denied",
    "error": "Only admins can view all requests"
}
```

### 422 Validation Error
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "title": ["The title field is required."],
        "description": ["The description field is required."]
    }
}
```

## Testing with cURL

### Create Request
```bash
curl -X POST http://127.0.0.1:8000/api/maintenance-requests \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Test Request",
    "description": "This is a test maintenance request",
    "company_name": "Test Company"
  }'
```

### Get User Requests
```bash
curl -X GET http://127.0.0.1:8000/api/maintenance-requests \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Get All Requests (Admin)
```bash
curl -X GET http://127.0.0.1:8000/api/maintenance-requests/all \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Update Status (Admin)
```bash
curl -X PUT http://127.0.0.1:8000/api/maintenance-requests/16/status \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "completed"
  }'
```
