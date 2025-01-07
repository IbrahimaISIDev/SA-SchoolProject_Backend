<?php
// app/Repositories/Interfaces/ISalleRepository.php
namespace App\Repositories\Interfaces;

interface ISalleRepository extends IBaseRepository
{
    public function getAvailableRooms(string $date, string $startTime, string $endTime);
    public function getRoomCapacity(int $salleId);
    public function checkRoomAvailability(int $salleId, string $date, string $startTime, string $endTime);
}