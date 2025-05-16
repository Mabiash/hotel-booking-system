/**
 * This file contains placeholder functions to simulate API calls and data fetching
 * For demonstration purposes only - replace with actual API calls in production
 */

// Simulated room data
const roomData = [
    {
        id: 1,
        name: "Deluxe King Room",
        type: "Deluxe",
        description: "Spacious room with a king-sized bed, modern amenities, and city views.",
        price: 199.99,
        capacity: 2,
        size: 35,
        view: "City View",
        image: "assets/images/rooms/deluxe-king.jpg",
        featured: true,
        status: "Available"
    },
    {
        id: 2,
        name: "Executive Suite",
        type: "Suite",
        description: "Luxurious suite featuring a separate living area, premium amenities, and panoramic ocean views.",
        price: 299.99,
        capacity: 2,
        size: 50,
        view: "Ocean View",
        image: "assets/images/rooms/executive-suite.jpg",
        featured: true,
        status: "Available"
    },
    {
        id: 3,
        name: "Family Room",
        type: "Family",
        description: "Specially designed room for families, featuring two queen beds and additional space for children.",
        price: 249.99,
        capacity: 4,
        size: 45,
        view: "Garden View",
        image: "assets/images/rooms/family-room.jpg",
        featured: true,
        status: "Available"
    }
];

// Simulated food data
const foodData = [
    {
        id: 1,
        name: "Continental Breakfast",
        category: "Breakfast",
        description: "Selection of freshly baked pastries, fruits, yogurt, and coffee or tea.",
        price: 12.99,
        image: "assets/images/food/continental-breakfast.jpg",
        available: true
    },
    {
        id: 2,
        name: "Club Sandwich",
        category: "Lunch",
        description: "Triple-decker sandwich with chicken, bacon, lettuce, tomato, and mayo.",
        price: 16.99,
        image: "assets/images/food/club-sandwich.jpg",
        available: true
    },
    {
        id: 3,
        name: "Grilled Salmon",
        category: "Dinner",
        description: "Fresh salmon fillet grilled to perfection, served with seasonal vegetables.",
        price: 24.99,
        image: "assets/images/food/grilled-salmon.jpg",
        available: true
    }
];

// Simulated booking data
const bookingData = [
    {
        id: 1,
        room: "Deluxe King Room",
        checkIn: "2023-06-15",
        checkOut: "2023-06-18",
        guests: 2,
        total: 599.97,
        status: "Confirmed"
    },
    {
        id: 2,
        room: "Executive Suite",
        checkIn: "2023-07-20",
        checkOut: "2023-07-25",
        guests: 2,
        total: 1499.95,
        status: "Pending"
    }
];

// Simulated food order data
const orderData = [
    {
        id: 1,
        date: "2023-06-16 19:30:00",
        items: 3,
        total: 54.97,
        status: "Completed"
    },
    {
        id: 2,
        date: "2023-07-21 12:15:00",
        items: 2,
        total: 29.98,
        status: "Pending"
    }
];

// Simulated user data
const userData = {
    name: "John Doe",
    email: "john.doe@example.com",
    phone: "123-456-7890"
};

// Function to generate placeholder images if real ones are missing
function generatePlaceholderImage(type, id) {
    const canvas = document.createElement('canvas');
    const context = canvas.getContext('2d');
    
    // Set canvas dimensions
    canvas.width = 300;
    canvas.height = 200;
    
    // Fill background
    context.fillStyle = type === 'room' ? '#1E293B' : '#0F172A';
    context.fillRect(0, 0, canvas.width, canvas.height);
    
    // Add text
    context.fillStyle = '#D4AF37';
    context.font = 'bold 20px Arial';
    context.textAlign = 'center';
    context.fillText(`88 HOTEL`, canvas.width / 2, canvas.height / 2 - 15);
    context.fillText(type === 'room' ? `Room ${id}` : `Food ${id}`, canvas.width / 2, canvas.height / 2 + 15);
    
    return canvas.toDataURL();
}

// Export placeholder data
window.placeholderData = {
    rooms: roomData,
    food: foodData,
    bookings: bookingData,
    orders: orderData,
    user: userData,
    generateImage: generatePlaceholderImage
};