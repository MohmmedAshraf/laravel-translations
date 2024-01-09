export type UserData = {
    name: string;
    email: string;
    email_verified_at: any | null;
};

export const useAuth = () => {
  return computed(
      () => usePage().props.user as UserData
  )
}
