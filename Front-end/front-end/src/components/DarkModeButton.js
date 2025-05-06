import React from "react"; 
import {ClientOnly, IconButton, Skeleton} from "@chakra-ui/react"
import {useColorMode} from "./ui/color-mode"
import {LuMoon, LuSun} from "react-icons/lu"

const DarkModeButton = () => {
	const {toggleColorMode, colorMode} = useColorMode();

	const handleToggle = () => {
		document.documentElement.classList.toggle('dark')
		toggleColorMode();
	}

return (
	 <ClientOnly fallback={<Skeleton boxSize="8" />}>
      <IconButton onClick={toggleColorMode} variant="outline" size="sm">
        {colorMode === "light" ? <LuSun /> : <LuMoon />}
      </IconButton>
    </ClientOnly>
	);
};

export default DarkModeButton;

