<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Advertisement;
use App\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdvertisementVoter extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';
    public const DELETE = 'delete';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof Advertisement;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        
        // if the user is anonymous, deny access
        if (!$user instanceof Employee) {
            return false;
        }

        /** @var Advertisement $advertisement */
        $advertisement = $subject;

        // Check if user is admin (assuming ROLE_ADMIN can manage all advertisements)
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Check if user is company owner
        if (in_array(Employee::ROLE_COMPANY_OWNER, $user->getRoles(), true) && 
            $advertisement->getCompany()->getOwner() === $user) {
            return true;
        }

        // Check if user is employed at the company
        $isEmployeeOfCompany = false;
        foreach ($user->getEmployedAt() as $company) {
            if ($company === $advertisement->getCompany()) {
                $isEmployeeOfCompany = true;
                break;
            }
        }

        // If not employed at the company, deny access
        if (!$isEmployeeOfCompany) {
            return false;
        }

        // At this point we know:
        // 1. User is not admin
        // 2. User is not company owner
        // 3. User is employee at the company
        
        switch ($attribute) {
            case self::VIEW:
                // Employees can view advertisements
                return true;
            case self::EDIT:
                // Employees can edit advertisements
                return true;
            case self::DELETE:
                // Only allow employees to delete if they created the advertisement
                return $advertisement->getCreatedBy() === $user;
        }

        return false;
    }
} 